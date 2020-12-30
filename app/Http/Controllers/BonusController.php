<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\BonusTransaction;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class BonusController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $bonuses = Bonus::where('user_id', Auth()->user()->id)->paginate(10);
    return view('bonus.list', ['bonuses' => $bonuses]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function convert_to_wallet_funds()
  {
    return view('bonus.convert_to_wallet');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store_convert_to_wallet_funds(Request $request)
  {
    $max = (Auth()->user()->available_wallet);
    request()->validate([
      'funding_amount' => 'required|numeric|min:100|max:' . $max,
    ]);

    if ($request->has('funding_amount')) {
      try {
        $new_trx = new Transaction();
        $new_trx->amount = $request->funding_amount;
        $new_trx->status = 'created';
        $new_trx->type = 'funding';
        $new_trx->user_id = Auth()->id();

        $new_bonus_trx = new Bonus();
        $new_bonus_trx->user_id = Auth()->user()->id;
        $new_bonus_trx->amount = -$request->funding_amount;
        $new_bonus_trx->status = 'created';
        $new_bonus_trx->type = 'withdraw';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $user = User::find(Auth()->user()->id)->first();
        $user->bonus -= $new_trx->amount;
        $user->wallet += $new_trx->amount;
        $user->update();

        return redirect()->route('user_home')->with('user-success', "Your wallet was successfully funded with {$request->amount} from your bonus");
      } catch (\Exception $e) {
        return back()->with('user-error', sprintf('Could not fund your wallet: %s', $e->getMessage()));
      }
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Bonus  $bonus
   * @return \Illuminate\Http\Response
   */
  public function show(Bonus $bonus)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Bonus  $bonus
   * @return \Illuminate\Http\Response
   */
  public function edit(Bonus $bonus)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Bonus  $bonus
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Bonus $bonus)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Bonus  $bonus
   * @return \Illuminate\Http\Response
   */
  public function destroy(Bonus $bonus)
  {
    //
  }
}
