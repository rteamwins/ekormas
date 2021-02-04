<?php

namespace App\Http\Controllers;

use App\Profit;
use App\Trade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TradeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $trades = Trade::where('user_id', Auth()->user()->id)->latest()->paginate(10);
    return view('trade.list', ['trades' => $trades]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $user = Auth()->user();
    if (Trade::whereUserId($user->id)->where('closing_at', '>', now())->exists()) {
      return back()->with('info', sprintf("You have a trade currently in session, you cant place any more trades during this period.", route('user_fund_wallet')));
    }
    if ($user->wallet >= $user->membership_plan->min_trading_capital) {
      return view('trade.create');
    } else {
      return back()->with('info', sprintf("You Do not have availble funds to trade with. Try funding you account and try again, <a href='%s'>Fund Account now</a>.", route('user_fund_wallet')));
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
      'trade_amount' => "required|numeric|min:10,max:{$avail_trade_amount}",
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
    $trader = User::where('id', Auth()->user()->id)->first();
    $new_trade = new Trade();
    $new_trade->amount = $request->trade_amount;
    $new_trade->user_id = Auth()->user()->id;


    $new_trade->earning = $this->calc_earn($trader, $request->trade_amount, $trade_duration);
    $new_trade->profit_percent = (($new_trade->earning / $new_trade->amount) * 100);
    $new_trade->completed = false;
    $days_duration = now()->addDays($trade_duration);
    $new_trade->closing_at = $days_duration;


    if ($request->trade_type == (3 || 2 || 1)) {
      $new_trade->method = "automatic";
    } else {
      $new_trade->method = "manual";
    }

    $new_trade->save();
    $trader->wallet -= $request->trade_amount;
    $trader->trading_capital += $request->trade_amount;
    $trader->update();

    //adding the profits database
    $minutesInDay = 1440;
    $tradeable_counts_per_day = $minutesInDay / 20;
    $tradeable_counts_per_week = ($tradeable_counts_per_day * 7);
    $tradeable_counts_per_duration = ($tradeable_counts_per_day * (7 * ($trade_duration / 7)));

    $gen_profits = $this->RandArrayToSum($tradeable_counts_per_duration, ($new_trade->amount * (1 + ($new_trade->profit_percent / 100))));

    $profits = [];
    $iter = 1;
    foreach ($gen_profits as $gen_profit) {
      $profit =  [
        'created_at' => now()->addMinutes($iter * 20)->format('Y-m-d H:i:s.u'),
        'updated_at' => now()->addMinutes($iter * 20)->format('Y-m-d H:i:s.u'),
        'applied' => false,
        'volume' => ($new_trade->amount - ($new_trade->amount * (1 + ($new_trade->profit_percent / 100))) * $gen_profit),
        'amount' => $gen_profit,
        'user_id' => $trader->id,
        'trade_id' => $new_trade->id,
      ];
      $iter++;
      $profits[] = $profit;
      // Log::info("iter: {$iter}....Tradecount: {$tradeable_counts_per_week}");
      if ($iter % $tradeable_counts_per_week == 0) {
        Profit::insert($profits);
        $profits = [];
      }
    }
    $profits = [];
    return redirect()->route('user_trade_history');
  }

  public function calc_earn($trader, $amount, $duration)
  {
    $tr_amt = $amount;
    $tr_per = ($trader->membership_plan->weekly_trading_percent / 100);
    $tr_ern = ($tr_amt * $tr_per);
    $wk_tr_amt = $trader->membership_plan->fee;
    $wk_tr_per = ($trader->membership_plan->weekly_membership_percent / 100);
    $wk_ern = ($wk_tr_amt * $wk_tr_per);
    return (($tr_ern + $wk_ern) * ($duration / 7));
  }
  function RandArrayToSum($numvalues, $total)
  {
    $out = [];
    $sum = 0;

    for ($i = 0; $i < $numvalues - 1; $i++) {
      $out[$i] = rand();
      $sum += $out[$i];
    }

    for ($i = 0; $i < $numvalues - 1; $i++) {
      $out[$i] /= $sum;
      $out[$i] *= $total;
    }


    return $out;
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
