<?php

namespace App\Jobs\CoinbaseWebhooks;

use Illuminate\Bus\Queueable;
use App\Transaction;
use App\Investment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Shakurov\Coinbase\Models\CoinbaseWebhookCall;


class HandleFailedCharge implements ShouldQueue
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
    Log::channel('coinbase')->info('handling...charge failed starting');
    try {
      $payload_obj = $this->webhookCall->payload;
      $transaction = Transaction::where(
        [
          'id' => $payload_obj['event']['data']['metadata']['trnx_id'],
          'user_id' => $payload_obj['event']['data']['metadata']['user_id']
        ]
      )->first();
      $transaction->status = 'failed';

      $transaction->update();
      $crypto_transaction = $transaction->method;
      Log::channel('coinbase')->info("crypto_trnx: " . $crypto_transaction->id);
      Log::channel('coinbase')->info("crypto_trnx_user: " . $transaction->user_id);
      $crypto_transaction->status = 'failed';
      $crypto_transaction->update();
    } catch (\Exception $e) {
      Log::channel('coinbase')->error(sprintf('Error handling Failed Charged: ', $e->getMessage()));
    }
    Log::channel('coinbase')->info('handling...charge failed completed');
    Log::channel('coinbase')->info("===================================================");
  }
}
