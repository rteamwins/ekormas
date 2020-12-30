<?php

namespace App\Jobs;

use Akaunting\Firewall\Models\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadAddProfitJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $user_id;
  protected $trade_id;
  protected $total_amount;
  protected $profit_count;
  /**
   * Create a new job instance.
   *
   * @return void
   */

  public function __construct($user_id, $trade_id, $total_amount, $profit_count)
  {
    $this->user_id = $user_id;
    $this->trade_id = $trade_id;
    $this->total_amount = $total_amount;
    $this->profit_count = $profit_count;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $profit_amounts = $this->RandArrayToSum($this->profit_count, $this->total_amount);
    $pc = count($profit_amounts) - 1;
    for ($i = 0; $i < $pc; $i++) {
      AddProfitToTrades::dispatch($this->user_id, $this->trade_id, $profit_amounts[$i])
        ->onQueue('profit')
        ->delay(now()->addMinutes($i * 15));
    }
  }
  function RandArrayToSum($numvalues, $total)
  {
    $out = [];
    $sum = 0;

    for ($i = 0; $i < $numvalues - 1; $i++) {
      $out[$i] = rand();
      $sum += $out[$i];
    }

    for ($i = 0; $i < $numvalues - 1; $i++) {
      $out[$i] /= $sum;
      $out[$i] *= $total;
    }


    return $out;
  }
}
