<?php

use App\State;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::create([
      'name' => 'system profile',
      'email' => 'system@thegreenlifemarket.shop',
      'password' => Hash::make('joeslim1'),
      'role' => 'admin',
      'phone' => '08174310668',
      'username' => 'system',
      'country_code' => 'CM',
      'state_id' => 2221788,
      'lga_id' => 2225280,
      'membership_plan_id' => 7,
      'activated_at' => now()
    ]);
  }
}
