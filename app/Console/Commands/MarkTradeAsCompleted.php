<?php

namespace App\Console\Commands;

use App\Trade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkTradeAsCompleted extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'trade:markAsCompleted';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Mark Trade with closing at less than or equal now and award profits';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $counter = 1;
    Log::info('Mark trade as completed job Started');
    $trades = Trade::with(['user:id,wallet'])->where('closing_at', '<=', now())->whereCompleted(false)->get();
    foreach ($trades as $trade) {
      Log::info(sprintf('Processing Data: %s ', $counter));
      $trade->user->wallet += ($trade->amount + $trade->earning);
      $trade->user->trading_capital -= $trade->amount;
      $trade->user->update();
      $trade->completed = true;
      $trade->update();
      Log::info(sprintf('Added $%s to User: %s', number_format($trade->amount + $trade->earning), $trade->user->id));
      Log::info(sprintf('Marked Trade: %s as Completed', $trade->id));
      $counter++;
    }
    Log::info('Mark trade as completed job Ended');
    $counter = null;
    return 0;
  }
}
