<?php

namespace App\Http\Controllers;

use App\Trade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $trades = Trade::where('user_id', Auth()->user()->id)->paginate(10);
    return view('trade.list', ['trades' => $trades]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    if (Auth()->user()->available_wallet < Auth()->user()->membership_plan->max_trading_capital) {
      return view('trade.create');
    } else {
      return back()->with('user-info', sprintf("You Do not have availble funds to trade with. Try funding you account and try again, <a href='%s'>Fund Account now</a>.", route('user_fund_wallet')));
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $avail_trade_amount = Auth()->user()->wallet - Auth()->user()->membership_amount;
    $this->validate($request, [
      'trade_amount' => "required|numeric|min:100,max:{$avail_trade_amount}",
      'trade_type' => "required|numeric|in:0,1,2,3",
    ]);

    if ($request->trade_type == 1) {
      $trade_duration = 28;
    } elseif ($request->trade_type == 2) {
      $trade_duration = 56;
    } elseif ($request->trade_type == 3) {
      $trade_duration = 84;
    } else {
      $trade_duration = 7;
    }
    $trader = User::find(Auth()->user()->id)->first();
    $new_trade = new Trade();
    $new_trade->amount = $request->trade_amount;
    $new_trade->earning = (($request->trade_amount * (2 / 100)) * ($trade_duration / 7));
    $new_trade->user_id = Auth()->user()->id;
    $new_trade->profit_percent = $trader->membership_plan->weekly_trading_percent;
    $new_trade->completed = false;
    $new_trade->closing_at = now()->addDays($trade_duration);
    if ($request->trade_type == (3 || 2 || 1)) {
      $new_trade->method = "automatic";
    } else {
      $new_trade->method = "manual";
    }
    $new_trade->save();
    $trader->wallet -= $request->trade_amount;
    $trader->trading_capital += $request->trade_amount;
    $trader->update();
    return redirect()->route('user_trade_history');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Trade  $trade
   * @return \Illuminate\Http\Response
   */
  public function show(Trade $trade)
  {
    //
  }
}
