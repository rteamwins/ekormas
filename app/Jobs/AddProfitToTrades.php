<?php

namespace App\Jobs;

use App\Profit;
use App\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddProfitToTrades implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $user_id;
  protected $trade_id;
  protected $profit_amount;
  /**
   * Create a new job instance.
   *
   * @return void
   */

  public function __construct($user_id, $trade_id, $profit_amount)
  {
    $this->user_id = $user_id;
    $this->trade_id = $trade_id;
    $this->profit_amount = $profit_amount;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    Profit::create([
      'userid' => $this->user_id,
      'trade_id' => $this->trade_id,
      'amount' => $this->profit_amount,
    ]);
  }
}
