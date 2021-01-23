<?php

namespace App\Console\Commands;

use App\Profit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkProfitAsApplied extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'profits:apply';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Apply Profits within time frame';

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
    Log::info('Mark failed transaction job Started');
    Profit::where('applied', false)->where('created_at', '<=', now())->update(['applied' => true]);
    Log::info('Mark failed transaction job Ended');
    return 0;
  }
}
