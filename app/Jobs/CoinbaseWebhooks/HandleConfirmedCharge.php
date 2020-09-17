<?php

namespace App\Jobs\CoinbaseWebhooks;

use Illuminate\Bus\Queueable;
use App\Transaction;
use App\Investment;
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
       $payments= $payload_obj['event']['data']['payments'];
      foreach ($payments as $payment) {
          $amount_confirmed += $payment['value']['local']['amount'];
        }
      $transaction = Transaction::updateOrCreate(
        [
          'investment_id' => $payload_obj['event']['data']['metadata']['investment_id'],
          'type' => $payload_obj['event']['data']['metadata']['type']
        ],
        [
          'amount' => $amount_confirmed,
          'status' => 'confirmed',
          'charge_id' => $payload_obj['event']['data']['id'],
          'charge_code' => $payload_obj['event']['data']['code'],
          'unresolved_context' => '',
          'recieving_wallet_address' => $payload_obj['event']['data']['addresses']['bitcoin'],
          'created_at' => $payload_obj['event']['data']['created_at']
        ],
      );
      $investment = Investment::find($payload_obj['event']['data']['metadata']['investment_id']);
      if($investment->status != 'confirmed'){
          $investment->total_amount = $amount_confirmed;
          $investment->status = 'confirmed';
          $investment->update();
          $investor = User::find($payload_obj['event']['data']['metadata']['user_id']);
          $investor->wallet_amount = $investor->wallet_amount + $amount_confirmed;
          $investor->update();
      Log::info(sprintf('handled confirmed Charged: ', $payload_obj['event']['data']['id']));
      if ($investor->activated == null) {
          $investor->wallet_amount = $investor->wallet_amount + 5;
          $investor->update();
        try {
          $lv_1_ref = User::where('id',$investor->sponsor)->firstOrFail();
          $lv_1_ref->bonus_amount += ($investment->total_amount * 0.05);
          $lv_1_ref->update();
        } catch (ModelNotFoundException $e) {
          Log::error(sprintf("Unable to give out Level 1 bonus: couldn't find  a sponsor user with id: %s", $investor->sponsor));
        }
        try {
          $lv_2_ref = User::where('id',$lv_1_ref->sponsor)->firstOrFail();
          $lv_2_ref->bonus_amount += ($investment->total_amount * 0.03);
          $lv_2_ref->update();
        } catch (ModelNotFoundException $e) {
          Log::error(sprintf("Unable to give out Level 2 bonus: couldn't find  a sponsor user with id: %s", $lv_1_ref->sponsor));
        }
        try {
          $lv_3_ref = User::where('id',$lv_2_ref->sponsor)->firstOrFail();
          $lv_3_ref->bonus_amount += ($investment->total_amount * 0.01);
          $lv_3_ref->update();
        } catch (ModelNotFoundException $e) {
          Log::error(sprintf("Unable to give out Level 3 bonus: couldn't find  a sponsor user with id: %s", $lv_2_ref->sponsor));
        }
        try {
          $lv_4_ref = User::where('id',$lv_3_ref->sponsor)->firstOrFail();
          $lv_4_ref->bonus_amount += ($investment->total_amount * 0.01);
          $lv_4_ref->update();
        } catch (ModelNotFoundException $e) {
          Log::error(sprintf("Unable to give out Level 4 bonus: couldn't find  a sponsor user with id: %s", $lv_3_ref->sponsor));
        }
        $investor->activated_at = Carbon::now();
        $investor->update();
      }
      }else{
          Log::info(sprintf('handled confirmed Charged: ', $payload_obj['event']['data']['id']));
      }

    } catch (\Exception $e) {
      Log::error(sprintf('Error handling confirmed Charged: ', $e->getMessage()));
    }
  }
}
