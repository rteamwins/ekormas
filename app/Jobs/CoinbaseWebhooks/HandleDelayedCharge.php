<?php

namespace App\Jobs\CoinbaseWebhooks;

use Illuminate\Bus\Queueable;
use App\Transaction;
use App\Investment;
use App\MembershipPlan;
use App\Order;
use App\RegistrationCredit;
use App\RegistrationCreditPurchase;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Shakurov\Coinbase\Models\CoinbaseWebhookCall;


class HandleDelayedCharge implements ShouldQueue
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
    Log::info('handling...charge delayed starting');
    try {
      $payload_obj = $this->webhookCall->payload;
      $amount_confirmed = 0;
      $payments = $payload_obj['event']['data']['payments'];
      foreach ($payments as $payment) {
        $amount_confirmed += $payment['value']['local']['amount'];
      }
      $user = User::where('id', $payload_obj['event']['data']['metadata']['user_id'])->first();

      $transaction = Transaction::where(
        [
          'id' => $payload_obj['event']['data']['metadata']['trnx_id'],
          'user_id' => $payload_obj['event']['data']['metadata']['user_id']
        ]
      )->first();
      $transaction->status = 'confirmed';

      $transaction->update();

      $crypto_transaction = $transaction->method;
      $crypto_transaction->status = 'confirmed';
      $crypto_transaction->update();
      Log::info("crypto_trnx: " . $crypto_transaction->id);
      Log::info("crypto_trnx_user: " . $transaction->user_id);
      if ($transaction->type == 'user_registration_fee') {
        Log::info('handling...user reg payment');
        $membership_plan = MembershipPlan::whereSlug($payload_obj['event']['data']['metadata']['membership_plan'])->first();
        Log::info('processing...user reg payment: ' . $user->username);
        $user->membership_plan_id = $membership_plan->id;
        $user->wallet += $membership_plan->min_trading_capital;
        $user->activated_at = now();
        $user->update();
        $user->give_ancestor_referal_bonus();
        if ($user->parent->children->count() == 2) {
          $user->check_for_bonus_eligible_ancestors($user);
        }
      } else if ($transaction->type == 'user_wallet_funding') {
        Log::info('handling...user wallet fund');
        $transaction->update(['amount' => $amount_confirmed]);
        Log::info('handling...user wallet fund trnx_id: ' . $user->username);
        $user->wallet += $amount_confirmed;
        $user->update();
        Log::info('handling...user wallet fund...completed');
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
      Log::info('handling...user reg payment...completed');
    } catch (\Exception $e) {
      Log::error(sprintf('Error handling confirmed Charged: %s', $e->getMessage()));
    }
    Log::info('handling...charge delayed completed');
  }
}
