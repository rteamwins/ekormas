<?php

use App\Alert;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Alert::create([
          'message'=>'Welcome to The Green Life Market, we are pleased to have you on board.',
          'status'=>'active'
        ]);
    }
}
