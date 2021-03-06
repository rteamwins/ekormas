<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AwardDailySalesPoint extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sales_point:award_daily';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Award points to users based on the users introduced daily';

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
    Log::channel('point')->info('Award Daily Points job Started');
    $users = User::get();
    foreach ($users as $user) {
      $user->calculate_sales_bonus();
    }
    Log::channel('point')->info('Award Daily Points job Ended');
    return 0;
  }
}
