<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\KYC;
use App\LocalPay;
use App\Transaction;
use App\User;
use App\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $kyc = KYC::select('id', 'status', 'amount', 'fee', 'created_at', 'updated_at', 'deleted_at', DB::raw("'kyc' as type"))
      ->where('user_id', Auth()->user()->id);
    $withdrawals = Withdraw::select('id', 'status', 'amount', 'fee', 'created_at', 'updated_at', 'deleted_at', DB::raw("'bitcoin' as type"))
      ->where('user_id', Auth()->user()->id);
    $local_pays = LocalPay::select('id', 'status', 'amount', 'fee', 'created_at', 'updated_at', 'deleted_at', DB::raw("'local' as type"))
      ->where('user_id', Auth()->user()->id)
      ->union($withdrawals)
      ->union($kyc)
      ->latest()
      ->paginate(10);
    return view('withdraw.list', ['withdrawals' => $local_pays]);
  }

  /* Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function admin_index_json()
  {
    $withdraws = Withdraw::with(['user:id,name,phone,username'])
      ->latest()
      ->paginate(20);
    return response()->json($withdraws, Response::HTTP_OK);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $agents = User::select("name", 'username')->where('role', 'agent')->get()->makeHidden(['wallet']);
    return view('withdraw.create', ['agents' => $agents]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $max = (Auth()->user()->wallet * ((100 - 5) / 100));
    $this->validate($request, [
      'withdraw_type' => 'required|string|in:bitcoin,kyc,local',
      'amount' => 'required|numeric|min:10|max:' . $max,
      'agent' => 'required_if:withdraw_type,local|alpha_dash|exists:users,username',
      'medium_name' => 'required_if:withdraw_type,local|string',
      'medium_account_name' => 'required_if:withdraw_type,local|string',
      'medium_account_number' => 'required_if:withdraw_type,local|string',
      'btc_address' => 'required_if:withdraw_type,bitcoin|alpha_num|min:20',
    ]);
    if ($request->withdraw_type == 'bitcoin') {
      $withdraw = new Withdraw();
      $withdraw->amount = $request->amount;
      $withdraw->fee = ((5 / 100) * $request->amount);
      $withdraw->destination_wallet_address = $request->btc_address;
      $withdraw->status = 'created';
      $withdraw->user_id = Auth()->user()->id;
      $withdraw->save();
    } else if ($request->withdraw_type == 'kyc') {
      $withdraw = new KYC();
      $withdraw->amount = $request->amount;
      $withdraw->fee = ((5 / 100) * $request->amount);
      $withdraw->user_id = $request->user()->id;
      $withdraw->status = 'created';
      $withdraw->save();
    } else {
      $agent = User::whereUsername($request->agent)->firstOrFail();
      $withdraw = new LocalPay();
      $withdraw->amount = $request->amount;
      $withdraw->fee = ((5 / 100) * $request->amount);
      $withdraw->user_id = $request->user()->id;
      $withdraw->agent_id = $agent->id;
      $withdraw->pop = null;
      $withdraw->bank_name = $request->medium_name;
      $withdraw->account_name = $request->medium_account_name;
      $withdraw->account_number = $request->medium_account_number;
      $withdraw->status = 'created';
      $withdraw->save();
    }
    $user = User::find(Auth()->user()->id);
    $user->wallet -= ($withdraw->amount + $withdraw->fee);
    $user->update();

    $admin = User::whereRole("admin")->firstOrFail();
    $new_trx = new Transaction();
    $new_trx->amount = ($withdraw->fee / 2);
    $new_trx->status = 'created';
    $new_trx->type = 'withdrawal_bonus';
    $new_trx->user_id = $admin->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $admin->id;
    $new_bonus_trx->amount = ($withdraw->fee / 2);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'service_charge_initial';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $admin->bonus += $new_trx->amount;
    $admin->update();
    return redirect()->route('user_withdraw_fund_history')->with('user-sucess', "Your {number_format($request->amount,2)} {$request->withdraw_type} withdraw request was successful");
  }


  /**
   * enable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function admin_confirm($id)
  {
    $bitcoin_withdraw = Withdraw::whereId($id)->firstOrFail();
    $bitcoin_withdraw->status = 'completed';
    $bitcoin_withdraw->update();

    $new_trx = new Transaction();
    $new_trx->amount = ($bitcoin_withdraw->fee / 2);
    $new_trx->status = 'created';
    $new_trx->type = 'withdrawal_bonus';
    $new_trx->user_id = auth()->user()->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = auth()->user()->id;
    $new_bonus_trx->amount = ($bitcoin_withdraw->fee / 2);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'service_charge_final';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    auth()->user()->bonus += $new_trx->amount;
    auth()->user()->update();

    $response = 'Bitcoin Withdraw Sent and Confirmed';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * disable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function admin_decline($id)
  {
    $bitcoin_withdraw = Withdraw::whereId($id)->firstOrFail();
    $bitcoin_withdraw->status = 'cancelled';
    $bitcoin_withdraw->update();

    $user = User::find($bitcoin_withdraw->user->id);
    $user->wallet += ($bitcoin_withdraw->amount + ($bitcoin_withdraw->fee / 2));
    $user->update();

    $admin = User::whereRole("admin")->firstOrFail();
    $new_trx = new Transaction();
    $new_trx->amount = - ($bitcoin_withdraw->fee / 2);
    $new_trx->status = 'created';
    $new_trx->type = 'withdrawal_bonus';
    $new_trx->user_id = $admin->id;


    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $admin->id;
    $new_bonus_trx->amount = - ($bitcoin_withdraw->fee / 2);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'service_charge_initial_reversed';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $admin->bonus += $new_trx->amount;
    $admin->update();
    $response = 'Bitcoin Payment Request Rejected';
    return response()->json($response, Response::HTTP_OK);
  }

  public function admin_index_request()
  {
    return view('withdraw.list_bitcoin_request');
  }
}
