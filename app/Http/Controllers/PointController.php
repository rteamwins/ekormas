<?php

namespace App\Http\Controllers;

use App\Point;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class PointController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $points = Point::where('user_id', Auth()->user()->id)->paginate(10);
    return view('point.list', ['points' => $points]);
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index_nominees()
  {
    $nominees = User::with(['membership_plan'])->select('referer', 'name', 'id', 'username', 'email', 'phone', 'dormant_points', 'created_at', 'membership_plan_id')
      ->where('role', 'user')->whereNotNull('membership_plan_id')->orderBy('dormant_points', 'desc')->paginate(10);
    return view('point.list_nominees', ['users' => $nominees]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function convert_points_to_wallet_funds()
  {
    if (auth()->user()->active_points < 102) {
      return back()->with('user-info', "You Do not have enough points to convert, you need " . (102 - auth()->user()->active_points) . " more");
    }
    return view('point.convert_to_wallet');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store_convert_points_to_wallet_funds(Request $request)
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

        $new_bonus_trx = new Point();
        $new_bonus_trx->user_id = Auth()->user()->id;
        $new_bonus_trx->amount = -$request->funding_amount;
        $new_bonus_trx->status = 'created';
        $new_bonus_trx->type = 'wallet_convert';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $user = User::find(Auth()->user()->id)->first();
        $user->active_points -= $new_trx->amount;
        $user->wallet += $new_trx->amount;
        $user->update();

        $new_trx = new Transaction();
        $new_trx->amount = ($request->funding_amount * 0.02);
        $new_trx->status = 'created';
        $new_trx->type = 'funding_fee';
        $new_trx->user_id = Auth()->id();

        $new_bonus_trx = new Point();
        $new_bonus_trx->user_id = Auth()->user()->id;
        $new_bonus_trx->amount = - ($request->funding_amount * 0.02);
        $new_bonus_trx->status = 'created';
        $new_bonus_trx->type = 'point_convert_fee';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        // $user = User::find(Auth()->user()->id)->first();
        Auth()->user()->active_points -= $new_trx->amount;
        Auth()->user()->wallet += $new_trx->amount;
        Auth()->user()->update();

        $admin = User::whereRole("admin")->firstOrFail();
        $new_trx = new Transaction();
        $new_trx->amount = ($request->funding_amount * 0.02);
        $new_trx->status = 'created';
        $new_trx->type = 'point_convert';
        $new_trx->user_id = $admin->id;

        $new_bonus_trx = new Point();
        $new_bonus_trx->user_id = $admin->id;
        $new_bonus_trx->amount = ($request->funding_amount * 0.02);
        $new_bonus_trx->status = 'created';
        $new_bonus_trx->type = 'point_convert_charge';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $admin->bonus += $new_trx->amount;
        $admin->update();
        return redirect()->route('user_home')->with('user-success', "Your wallet was successfully funded with {$request->amount} from your points");
      } catch (\Exception $e) {
        return back()->with('user-error', sprintf('Could not fund your wallet: %s', $e->getMessage()));
      }
    }
  }
}
