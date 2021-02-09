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
    $bonuses = Bonus::where('user_id', Auth()->user()->id)->latest()->paginate(10);
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
    $max = ((Auth()->user()->bonus) * 0.98);
    request()->validate([
      'funding_amount' => 'required|numeric|min:10|max:' . $max,
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
        $new_bonus_trx->type = 'wallet_convert';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $user = User::where('id',Auth()->user()->id)->first();
        $user->bonus -= $new_trx->amount;
        $user->wallet += $new_trx->amount;
        $user->update();

        //collect service charge
        $new_sc_trx = new Transaction();
        $new_sc_trx->amount = - ($request->funding_amount * 0.02);
        $new_sc_trx->status = 'created';
        $new_sc_trx->type = 'funding_fee';
        $new_sc_trx->user_id = $user->id;

        $new_sc_bonus_trx = new Bonus();
        $new_sc_bonus_trx->user_id = $user->id;
        $new_sc_bonus_trx->amount = - ($request->funding_amount * 0.02);
        $new_sc_bonus_trx->status = 'created';
        $new_sc_bonus_trx->type = 'bonus_convert_fee';
        $new_sc_bonus_trx->save();
        $new_sc_bonus_trx->transaction()->save($new_sc_trx);
        $new_sc_trx->status = 'completed';
        $new_sc_trx->update();
        // $user = User::where('id',Auth()->user()->id)->first();
        $user->bonus -= ($request->funding_amount * 0.02);
        $user->update();

        //receive service charge
        $admin = User::whereRole("admin")->firstOrFail();
        $new_scr_trx = new Transaction();
        $new_scr_trx->amount = ($request->funding_amount * 0.02);
        $new_scr_trx->status = 'created';
        $new_scr_trx->type = 'bonus';
        $new_scr_trx->user_id = $admin->id;

        $new_scr_bonus_trx = new Bonus();
        $new_scr_bonus_trx->user_id = $admin->id;
        $new_scr_bonus_trx->amount = ($request->funding_amount * 0.02);
        $new_scr_bonus_trx->status = 'created';
        $new_scr_bonus_trx->type = 'bonus_convert_charge';
        $new_scr_bonus_trx->save();
        $new_scr_bonus_trx->transaction()->save($new_trx);
        $new_scr_trx->status = 'completed';
        $new_scr_trx->update();
        $admin->bonus += $new_scr_trx->amount;
        $admin->update();
        return redirect()->route('user_home')->with('success', "Your wallet was successfully funded with {$request->amount} from your bonus");
      } catch (\Exception $e) {
        return back()->with('error', sprintf('Could not fund your wallet: %s', $e->getMessage()));
      }
    }
  }
}
