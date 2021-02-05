<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Referal;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = '/';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
  }

  public function showRegistrationForm($ref_code, $placement_id)
  {
    $req['referer'] = $ref_code;
    $req['placement_id'] = $placement_id;
    $val_req = Validator::make($req, [
      'referer' => 'required|alpha_dash|min:3|max:25|exists:users,username',
      'placement_id' => 'required|integer|min:100000|max:9999999999|exists:users',
    ]);
    if ($val_req->fails()) {
      return abort(404);
    }
    $ref = User::where('placement_id', $placement_id)->first();
    if ($ref->children()->count() < 2) {
      return view('auth.register')->with(['referer' => $ref_code, 'placement_id' => $placement_id]);
    } else {
      return redirect()->route('home')->with('info', 'No more spaces on direct legs, ask for new Registration Link');
    }
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    User::fixTree();
    if (array_key_exists('placement_id', $data) && $data['placement_id'] != (null || "")) {
      $parent_node = User::select('id')->where('placement_id', $data['placement_id'])->first();
      $children_count = $parent_node->children()->count();
      Log::info("Children Count: " . $children_count);
      if ($children_count <= 1) {
        $data['parent_id'] = $parent_node->id;
      } else {
        throw ValidationException::withMessages(['placement_id' => 'No Space in requested placement, please ask for new Placement ID']);
      }
    }
    return Validator::make($data, [
      'referer' => 'required|alpha_dash|min:3|max:25|exists:users,username',
      'placement_id' => 'required|integer|min:1000000000|max:9999999999|exists:users',
      'name' => 'required|string|max:25|min:3',
      'phone' => 'required|numeric|unique:users',
      'username' => 'required|alpha_dash|max:25|min:3|unique:users,username',
      'email' => 'required|email|max:150|min:5|unique:users,email',
      'password' => 'required|string',
      'confirm_password' => 'required|same:password',
    ]);
  }



  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\User
   */
  protected function create(array $data)
  {
    if (array_key_exists('referer', $data) && $data['referer'] != (null || "")) {
      $referer = User::select('id')->whereUsername($data['referer'])->firstOrFail();
      $data['referer'] = $referer->id;
    } else {
      unset($data['referer']);
    }

    $parent_node = User::select('id')->where('placement_id', $data['placement_id'])->first();
    $data['parent_id'] = $parent_node->id;
    $data['role'] = 'user';
    unset($data['placement_id']);
    $data['password'] = Hash::make($data['password']);
    return User::create($data);
  }
}
