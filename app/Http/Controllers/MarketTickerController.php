<?php

namespace App\Http\Controllers;

use App\MarketTicker;
use App\Profit;
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

    // $jcat = User::where('username','jcat')->first();
    // $jkp2 = User::where('username','jkp2')->first();
    // $jcat->appendToNode($jkp2)->save();
    // $jsnake = User::where('username','jsnake')->first();
    // $jkp3 = User::where('username','jkp3')->first();
    // $jsnake->appendToNode($jkp3)->save();

    $user = User::where('id', 12)->first();
    $user->give_ancestor_referal_bonus();
    Log::info("User Siblings Count: " . $user->parent->children->count());
    if ($user->parent->children->count() == 2) {
      $user->check_for_bonus_eligible_ancestors($user);
    }


    $date_ranges = [];
    $first_date = Profit::oldest()->first()->created_at;
    $last_date = Profit::latest()->first()->created_at;
    while ($last_date->greaterThan($first_date)) {
      $date_ranges[] = $first_date->addHour()->getTimestamp();
    }

    $OHLCs = [];
    array_pop($date_ranges);
    foreach ($date_ranges as $date) {
      $open_time = now()->setTimestamp($date);
      $close_time = now()->setTimestamp($date)->addHour();
      $OHLC['date'] = $date ?? null;
      $profits = Profit::whereBetween('created_at', [$open_time, $close_time])->get();
      $OHLC['open'] = $profits->first()->amount ?? null;
      $OHLC['high'] = $profits->max('amount') ?? null;
      $OHLC['low'] = $profits->min('amount') ?? null;
      $OHLC['close'] = $profits->last()->amount ?? null;
      $OHLC['volume'] = $profits->avg('volume') ?? null;
      $OHLCs[] = $OHLC;
    }
    return $OHLCs;
  }
}
