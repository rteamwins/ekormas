<?php

namespace App\Http\Controllers;

use App\RegistrationCredit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegistrationCreditController extends Controller
{
  public function index()
  {
    return view('membership.list');
  }
  function index_json()
  {
    $rcs = RegistrationCredit::with(['consumer:id,name,username,phone,email'])->whereUserId(auth()->user()->id)->whereNull('used_by')->paginate(10);
    return response()->json($rcs, Response::HTTP_OK);
  }

  public function gift()
  {
    return view('membership.gift');
  }

  public function gift_store(Request $request)
  {
    $this->validate($request, [
      'receiver_username' => 'required|string|exists:users,username,role,agent',
      'plan' => "required|alpha_dash|in:onyx,pearl,ruby,gold,sapphire,emerald,diamond",
      "quantity" => "required|digits_between:1,50",
    ]);

    $user = User::where('username', $request->receiver_username)->first();
    for ($i = 0; $i < $request->quantity; $i++) {
      $new_rc = new RegistrationCredit([
        'plan' => $request->plan,
        "user_id" => $user->id,
        "status" => 'created',
        "used_by" => null,
      ]);
      $new_rc->save();
    }
    return redirect()->route('user_home')->with('success', "You successfully gifted Agent: {$user->username} with {$request->quantity}X of {$request->plan} Registration Plan");
  }
}
