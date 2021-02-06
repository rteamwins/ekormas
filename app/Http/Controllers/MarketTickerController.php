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
    $user = User::where('id', 11)->first();
    $user->check_for_bonus_eligible_ancestors($user);


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
