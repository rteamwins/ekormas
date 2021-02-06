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
    $ancestors = User::defaultOrder()->with(['membership_plan:id,fee,name'])
      ->ancestorsOf(11, ['id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'phone', 'membership_plan_id', 'created_at', 'activated_at']);
    $leg_count = [];
    foreach ($ancestors as $ancestor) {
      $ancestor_directline_count = $ancestor->children->count();
      $leg_count[$ancestor->username]['name'] = $ancestor->name;
      if ($ancestor_directline_count > 0) {
        if ($ancestor_directline_count == 2) {
          $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = $ancestor->children->last()->membership_plan->fee ?? 0;
        } else {
          $leg_count[$ancestor->username]['left'] = 1;
          $leg_count[$ancestor->username]['right'] = 0;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = 0;
        }
      } else {
        $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
        $leg_count[$ancestor->username]['left_amount'] = $leg_count[$ancestor->username]['right_amount'] = 0;
      }
      $left_desc = User::descendantsOf($ancestor->children->first());
      $right_desc = User::descendantsOf($ancestor->children->last());

      $leg_count[$ancestor->username]['left'] += $left_desc->count();
      $leg_count[$ancestor->username]['right'] += $right_desc->count();

      $leg_count[$ancestor->username]['left_amount'] += $left_desc->sum('membership_plan.fee');
      $leg_count[$ancestor->username]['right_amount'] += $right_desc->sum('membership_plan.fee');

      // if ($leg_count[$ancestor->username]['left'] == $leg_count[$ancestor->username]['right']) {
      //   $ancestor->calculate_matching_bonus($leg_count[$ancestor->username]['left'] + $leg_count[$ancestor->username]['right']);
      // }
    }
    return $leg_count;


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
