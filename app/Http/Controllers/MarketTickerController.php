<?php

namespace App\Http\Controllers;

use App\MarketTicker;
use App\Profit;
use App\User;
use Illuminate\Http\Request;

class MarketTickerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return User::latest()->limit(5)->ancestorsOf(9);

    // $n = 1;
    // while ($n <= 20) {
    //   $arr["stage_{$n}"] = number_format(5 * ((100 / $n) / 100), 2);
    //   $n++;
    // }
    // return $arr;
    // return number_format((2 ** (2 * $n - 1)) + 2 ** (2 * $n));


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

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\MarketTicker  $marketTicker
   * @return \Illuminate\Http\Response
   */
  public function show(MarketTicker $marketTicker)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\MarketTicker  $marketTicker
   * @return \Illuminate\Http\Response
   */
  public function edit(MarketTicker $marketTicker)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\MarketTicker  $marketTicker
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, MarketTicker $marketTicker)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\MarketTicker  $marketTicker
   * @return \Illuminate\Http\Response
   */
  public function destroy(MarketTicker $marketTicker)
  {
    //
  }
}
