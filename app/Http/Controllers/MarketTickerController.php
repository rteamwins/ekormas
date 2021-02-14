<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\MarketTicker;
use App\MembershipPlan;
use App\Profit;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketTickerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {


    $user = User::where('id', 19)->first();
    $membership_plan = MembershipPlan::whereSlug('pearl')->first();
    $user->membership_plan_id = $membership_plan->id;
    $user->wallet += $membership_plan->min_trading_capital;
    $user->activated_at = now();
    $user->update();
    $user->refresh();

    //award registration fee to admin
    $admin = User::where('role', 'admin')->first();
    $new_admin_trx = new Transaction();
    $new_admin_trx->amount =  10;
    $new_admin_trx->status = 'created';
    $new_admin_trx->type = 'bonus';
    $new_admin_trx->user_id = $admin->id;

    $new_bonus_admin_trx = new Bonus();
    $new_bonus_admin_trx->user_id = $admin->id;
    $new_bonus_admin_trx->amount = 10;
    $new_bonus_admin_trx->status = 'created';
    $new_bonus_admin_trx->type = 'registration_fee_full';
    $new_bonus_admin_trx->save();
    $new_bonus_admin_trx->transaction()->save($new_admin_trx);
    $new_admin_trx->status = 'completed';
    $new_admin_trx->update();
    $admin->bonus += $new_admin_trx->amount;
    $admin->update();


    $user->give_ancestor_referal_bonus();
    if ($user->parent->children->count() == 2) {
      $user->check_for_bonus_eligible_ancestors($user);
    }
  }
}
