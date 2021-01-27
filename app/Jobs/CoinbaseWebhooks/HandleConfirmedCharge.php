<?php

namespace App\Jobs\CoinbaseWebhooks;

use App\CryptoTransaction;
use App\MembershipPlan;
use App\Order;
use App\RegistrationCredit;
use App\RegistrationCreditPurchase;
use Illuminate\Bus\Queueable;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Shakurov\Coinbase\Models\CoinbaseWebhookCall;


class HandleConfirmedCharge implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /** @var \Shakurov\Coinbase\Models\CoinbaseWebhookCall */
  public $webhookCall;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(CoinbaseWebhookCall $webhookCall)
  {
    $this->webhookCall = $webhookCall;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    try {
      $payload_obj = $this->webhookCall->payload;
      $amount_confirmed = 0;
      $payments = $payload_obj['event']['data']['payments'];
      foreach ($payments as $payment) {
        $amount_confirmed += $payment['value']['local']['amount'];
      }
      $user = User::whereUserId($payload_obj['event']['data']['metadata']['user_id'])->first();

      $transaction = Transaction::updateOrCreate(
        [
          'id' => $payload_obj['event']['data']['metadata']['trnx_id'],
          'user_id' => $payload_obj['event']['data']['metadata']['user_id']
        ],
        [
          'status' => 'confirmed',
        ],

      );
      $crypto_transaction = $transaction->method();
      $crypto_transaction->status = 'confirmed';
      $crypto_transaction->update();
      if ($transaction->type == 'user_registration_fee') {
        $membership_plan = MembershipPlan::whereSlug($payload_obj['event']['data']['metadata']['membership_plan'])->first();
        $user->membership_plan_id = $membership_plan->id;
        $user->wallet += $membership_plan->min_trading_capital;
        $user->update();
        $user->give_referal_bonus();
        if ($user->parent->children->count() == 2) {
          $this->check_for_bonus_eligible_ancestors($user);
        }
      } else if ($transaction->type == 'user_wallet_funding') {
        $transaction->update(['amount' => $amount_confirmed]);
        $user->wallet += $amount_confirmed;
        $user->update();
      } else if ($transaction->type == 'registration_credit_purchase') {
        $rc_purchase = RegistrationCreditPurchase::whereId($payload_obj['event']['data']['metadata']['rcp_id'])->first();
        $membership_plan = MembershipPlan::whereSlug($rc_purchase->plan)->first();
        for ($i = 0; $i < $rc_purchase->quantity; $i++) {
          RegistrationCredit::create([
            'status' => 'created',
            'user_id' => $user->id,
            'amount' => ($membership_plan->fee + $membership_plan->min_trading_capital + 10),
            'plan' => $rc_purchase->plan,
          ]);
        }
      } else if ($transaction->type == 'product_order') {
        $order = Order::whereCode($payload_obj['event']['data']['metadata']['order_code'])->whereStatus('created')->first();
        $order->status = 'confirmed';
        $order->update();
      }
    } catch (\Exception $e) {
      Log::error(sprintf('Error handling confirmed Charged: ', $e->getMessage()));
    }
  }
  public function check_for_bonus_eligible_ancestors(User $user)
  {
    $ancestors = User::defaultOrder()->with(['membership_plan:id,fee,name'])
      ->ancestorsOf($user->id, ['id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'total_points', 'phone', 'membership_plan_id', 'created_at', 'activated_at']);
    foreach ($ancestors as $ancestor) {
      $ancestor_directline_count = $ancestor->children->count();
      $leg_count[$ancestor->username]['name'] = $ancestor->name;
      if ($ancestor_directline_count > 0) {
        if ($ancestor_directline_count == 2) {
          $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = $ancestor->children->last()->membership_plan->fee ?? 0;
        } else {
          $leg_count[$ancestor->username]['left'] = 1;
          $leg_count[$ancestor->username]['right'] = 0;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = 0;
        }
      } else {
        $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
        $leg_count[$ancestor->username]['left_amount'] = $leg_count[$ancestor->username]['right_amount'] = 0;
      }
      $left_desc = User::descendantsOf($ancestor->children->first());
      $right_desc = User::descendantsOf($ancestor->children->last());

      $leg_count[$ancestor->username]['left'] += $left_desc->count();
      $leg_count[$ancestor->username]['right'] += $right_desc->count();

      $leg_count[$ancestor->username]['left_amount'] += $left_desc->sum('membership_plan.fee');
      $leg_count[$ancestor->username]['right_amount'] += $right_desc->sum('membership_plan.fee');
      if ($leg_count[$ancestor->username]['left'] == $leg_count[$ancestor->username]['right']) {
        $ancestor->calculate_matching_bonus($leg_count[$ancestor->username]['left'] + $leg_count[$ancestor->username]['right']);
      }
    }
  }
}
