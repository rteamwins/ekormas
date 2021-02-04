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


class HandlePendingCharge implements ShouldQueue
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
    Log::info('handling...charge pending starting');
    Log::info($this->webhookCall->payload);
    try {
      $payload_obj = $this->webhookCall->payload;
      $transaction = Transaction::updateOrCreate(
        [
          'id' => $payload_obj['event']['data']['metadata']['trnx_id'],
          'user_id' => $payload_obj['event']['data']['metadata']['user_id']
        ],
        [
          'status' => 'pending',
        ],

      );
      $crypto_transaction = $transaction->method();
      $crypto_transaction->status = 'pending';
      $crypto_transaction->update();
      Log::info('handling...charge pending starting');
    } catch (\Exception $e) {
      Log::error(sprintf('Error handling pending Charged: ', $e->getMessage()));
    }
  }
}
