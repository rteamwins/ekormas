<?php

use App\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $plans = [
      [
        'name' => 'Pearl',
        'slug' => 'pearl',
        'status' => 'active',
        'fee' => 100,
        'min_trading_capital' => 20,
        'max_trading_capital' => 100,
        'weekly_membership_percent' => 3,
        'weekly_trading_percent' => 1,
        // 'membership_cancellation_percent'=>'',
        // 'product_discount_percent'=>'',
        // 'product_resale_percent'=>'',
        // 'kyc_creation_percent'=>'',
        'referal_bonus_percent' => 5,
        'level1_downline_upgrade_bonus_percent' => 2
      ],
      [
        'name' => 'Ruby',
        'slug' => 'ruby',
        'status' => 'active',
        'fee' => 250,
        'min_trading_capital' => 50,
        'max_trading_capital' => 250,
        'weekly_membership_percent' => 3,
        'weekly_trading_percent' => 1.025,
        // 'membership_cancellation_percent'=>'',
        // 'product_discount_percent'=>'',
        // 'product_resale_percent'=>'',
        // 'kyc_creation_percent'=>'',
        'referal_bonus_percent' => 10,
        'level1_downline_upgrade_bonus_percent' => 2
      ],
      [
        'name' => 'Gold',
        'slug' => 'gold',
        'status' => 'active',
        'fee' => 500,
        'min_trading_capital' => 100,
        'max_trading_capital' => 500,
        'weekly_membership_percent' => 3,
        'weekly_trading_percent' => 1.05,
        // 'membership_cancellation_percent'=>'',
        // 'product_discount_percent'=>'',
        // 'product_resale_percent'=>'',
        // 'kyc_creation_percent'=>'',
        'referal_bonus_percent' => 10,
        'level1_downline_upgrade_bonus_percent' => 2
      ],
      [
        'name' => 'Sapphire',
        'slug' => 'sapphire',
        'status' => 'active',
        'fee' => 1000,
        'min_trading_capital' => 200,
        'max_trading_capital' => 700,
        'weekly_membership_percent' => 3,
        'weekly_trading_percent' => 1.125,
        // 'membership_cancellation_percent'=>'',
        // 'product_discount_percent'=>'',
        // 'product_resale_percent'=>'',
        // 'kyc_creation_percent'=>'',
        'referal_bonus_percent' => 10,
        'level1_downline_upgrade_bonus_percent' => 2
      ],
      [
        'name' => 'Emerald',
        'slug' => 'emerald',
        'status' => 'active',
        'fee' => 3000,
        'min_trading_capital' => 600,
        'max_trading_capital' => 1000,
        'weekly_membership_percent' => 3.5,
        'weekly_trading_percent' => 1.5,
        // 'membership_cancellation_percent'=>'',
        // 'product_discount_percent'=>'',
        // 'product_resale_percent'=>'',
        // 'kyc_creation_percent'=>'',
        'referal_bonus_percent' => 10,
        'level1_downline_upgrade_bonus_percent' => 2
      ],
      [
        'name' => 'Diamond',
        'slug' => 'diamond',
        'status' => 'active',
        'fee' => 5000,
        'min_trading_capital' => 1000,
        'max_trading_capital' => 2000,
        'weekly_membership_percent' => 3.5,
        'weekly_trading_percent' => 2,
        // 'membership_cancellation_percent'=>'',
        // 'product_discount_percent'=>'',
        // 'product_resale_percent'=>'',
        // 'kyc_creation_percent'=>'',
        'referal_bonus_percent' => 10,
        'level1_downline_upgrade_bonus_percent' => 2
      ],
    ];

    $planProgressBar = $this->command->getOutput()->createProgressBar(count($plans));
    $planProgressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% ');
    foreach ($plans as $plan) {
      MembershipPlan::create($plan);
      $planProgressBar->advance(count($plans));
    }
    $planProgressBar->finish();
  }
}
