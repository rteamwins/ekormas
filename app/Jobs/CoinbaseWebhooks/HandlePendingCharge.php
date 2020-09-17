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
    Log::info($this->webhookCall->payload);
    try{
    $payload_obj = $this->webhookCall->payload;
    $transaction = Transaction::updateOrCreate(
      [
        'investment_id' => $payload_obj['event']['data']['metadata']['investment_id'],
        'type' => $payload_obj['event']['data']['metadata']['type']
      ],
      [
        'amount' => $payload_obj['event']['data']['pricing']['local']['amount'],
        'status' => 'pending',
        'charge_id' => $payload_obj['event']['data']['id'],
        'charge_code' => $payload_obj['event']['data']['code'],
        'unresolved_context' => '',
        'recieving_wallet_address' => $payload_obj['event']['data']['addresses']['bitcoin'],
        'created_at' => $payload_obj['event']['data']['created_at']
      ],
    );
    Log::info($transaction);
    $investment = Investment::find($payload_obj['event']['data']['metadata']['investment_id']);
    $investment->status = 'pending';
    $investment->save();
    Log::info($investment);
  }catch(\Exception $e){
    Log::error(sprintf('Error handling pending Charged: ',$e->getMessage()));
  }
  }
}
