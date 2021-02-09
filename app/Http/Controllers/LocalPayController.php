<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\Funding;
use App\LocalPay;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocalPayController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $local_pays = LocalPay::whereUserId(Auth()->user()->id)->paginate(20);
    return view('withdraw.list_local_pay', ['local_pays' => $local_pays]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index_request()
  {
    // $local_pays = LocalPay::whereAgentId(Auth()->user()->id)->paginate(20);
    return view('withdraw.list_local_pay_request');
  }

  /* Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index_json()
  {
    $alerts = LocalPay::with(['user:id,name,phone'])
      ->whereAgentId(Auth()->user()->id)
      ->paginate(20);
    return response()->json($alerts, Response::HTTP_OK);
  }

  /**
   * enable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function confirm(Request $request, $id)
  {
    $this->validate($request, [
      'pop' => 'required|image|mimes:png,jpg,',
    ]);
    $local_pay = LocalPay::whereId($id)->firstOrFail();

    if ($request->hasFile('pop')) {
      $pop = $request->file('pop');
      $destination_path = public_path("images/pop");
      $image_name = $local_pay->id . "." . $pop->getClientOriginalExtension();
      $pop->move($destination_path, $image_name);
      $local_pay->pop = $image_name;
    }

    $local_pay->status = 'completed';
    $local_pay->update();

    $new_trx = new Transaction();
    $new_trx->amount = ($local_pay->fee / 2);
    $new_trx->status = 'created';
    $new_trx->type = 'withdrawal_bonus';
    $new_trx->user_id = auth()->user()->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = auth()->user()->id;
    $new_bonus_trx->amount = ($local_pay->fee / 2);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'service_charge_final';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    auth()->user()->bonus += $new_trx->amount;
    auth()->user()->update();

    $response = 'Local Payment Sent and Confirmed';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * disable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function decline($id)
  {
    $local_pay = LocalPay::whereId($id)->firstOrFail();
    $local_pay->status = 'cancelled';
    $local_pay->update();

    $user = User::where('id',$local_pay->user->id)->first();
    $user->wallet += ($local_pay->amount + ($local_pay->fee / 2));
    $user->update();

    $admin = User::whereRole("admin")->firstOrFail();
    $new_trx = new Transaction();
    $new_trx->amount = -($local_pay->fee / 2);
    $new_trx->status = 'created';
    $new_trx->type = 'withdrawal_bonus';
    $new_trx->user_id = $admin->id;


    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $admin->id;
    $new_bonus_trx->amount = -($local_pay->fee / 2);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'service_charge_initial_reversed';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $admin->bonus += $new_trx->amount;
    $admin->update();
    $response = 'Local Payment Request Rejected';
    return response()->json($response, Response::HTTP_OK);
  }
}
