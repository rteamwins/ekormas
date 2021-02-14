<?php

namespace App\Jobs\CoinbaseWebhooks;

use App\Bonus;
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
    Log::channel('coinbase')->info('handling...charge confirmed starting');
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
      Log::channel('coinbase')->info("crypto_trnx: " . $crypto_transaction->id);
      Log::channel('coinbase')->info("crypto_trnx_user: " . $transaction->user_id);
      if ($transaction->type == 'user_registration_fee') {
        Log::channel('coinbase')->info('handling...user reg payment');
        $membership_plan = MembershipPlan::whereSlug($payload_obj['event']['data']['metadata']['membership_plan'])->first();
        Log::channel('coinbase')->info('processing...user reg payment: ' . $user->username);
        $user->membership_plan_id = $membership_plan->id;
        $user->wallet += $membership_plan->min_trading_capital;
        $user->activated_at = now();
        $user->update();
        $user->refresh();

        //award registration fee to admin
        $admin = User::where('role', 'admin')->first();
        $new_admin_trx = new Transaction();
        $new_admin_trx->amount =  10;
        $new_admin_trx->status = 'created';
        $new_admin_trx->type = 'bonus';
        $new_admin_trx->user_id = $admin->id;

        $new_bonus_admin_trx = new Bonus();
        $new_bonus_admin_trx->user_id = $admin->id;
        $new_bonus_admin_trx->amount = 10;
        $new_bonus_admin_trx->status = 'created';
        $new_bonus_admin_trx->type = 'registration_fee_full';
        $new_bonus_admin_trx->save();
        $new_bonus_admin_trx->transaction()->save($new_admin_trx);
        $new_admin_trx->status = 'completed';
        $new_admin_trx->update();
        $admin->bonus += $new_admin_trx->amount;
        $admin->update();

        $user->give_ancestor_referal_bonus();
        if ($user->parent->children->count() == 2) {
          $user->check_for_bonus_eligible_ancestors($user);
        }
      } else if ($transaction->type == 'user_registration_fee_valentine') {
        Log::channel('coinbase')->info('handling...user reg valentine payment');
        $plan = $payload_obj['event']['data']['metadata']['membership_plan'];
        $plan = strstr($plan, "_", true);
        $membership_plan = MembershipPlan::whereSlug($plan)->first();
        Log::channel('coinbase')->info('processing...user reg valentine payment: ' . $user->username);
        $user->membership_plan_id = $membership_plan->id;
        $user->wallet += $membership_plan->min_trading_capital;
        $user->activated_at = now();
        $user->update();
        $user->refresh();

        //award registration fee to admin
        $admin = User::where('role', 'admin')->first();
        $new_admin_trx = new Transaction();
        $new_admin_trx->amount =  10;
        $new_admin_trx->status = 'created';
        $new_admin_trx->type = 'bonus';
        $new_admin_trx->user_id = $admin->id;

        $new_bonus_admin_trx = new Bonus();
        $new_bonus_admin_trx->user_id = $admin->id;
        $new_bonus_admin_trx->amount = 10;
        $new_bonus_admin_trx->status = 'created';
        $new_bonus_admin_trx->type = 'registration_fee_full';
        $new_bonus_admin_trx->save();
        $new_bonus_admin_trx->transaction()->save($new_admin_trx);
        $new_admin_trx->status = 'completed';
        $new_admin_trx->update();
        $admin->bonus += $new_admin_trx->amount;
        $admin->update();


        $user->give_ancestor_referal_bonus();
        if ($user->parent->children->count() == 2) {
          $user->check_for_bonus_eligible_ancestors($user);
        }
      } else if ($transaction->type == 'user_wallet_funding') {
        Log::channel('coinbase')->info('handling...user wallet fund');
        $transaction->update(['amount' => $amount_confirmed]);
        Log::channel('coinbase')->info('handling...user wallet fund trnx_id: ' . $user->username);
        $user->wallet += $amount_confirmed;
        $user->update();
        Log::channel('coinbase')->info('handling...user wallet fund...completed');
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
      Log::channel('coinbase')->error(sprintf('Error handling confirmed Charged: %s. File: %s. Line: %s', $e->getMessage(), $e->getFile(), $e->getLine()));
    }
    Log::channel('coinbase')->info('handling...charge confirmed completed');
    Log::channel('coinbase')->info("===================================================");
  }
}
