<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // $this->call(UserSeeder::class);
    DB::statement('SET FOREIGN_KEY_CHECKS  = 0;');
    $this->call(MembershipPlanSeeder::class);
    $this->call(CountrySeeder::class);
    $this->call(StateSeeder::class);
    $this->call(LgaSeeder::class);
    DB::statement('SET FOREIGN_KEY_CHECKS  = 1;');
  }
}
