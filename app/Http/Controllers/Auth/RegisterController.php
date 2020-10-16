<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'referer' => 'sometimes|nullable|alpha_num|min:3|max:25|exists:users,username',
      'first_name' => 'required|alpha|max:25|min:3',
      'last_name' => 'required|alpha|max:25|min:3',
      'phone' => 'required|numeric',
      'username' => 'required|alpha_dash|max:25|min:3|unique:users,username',
      'email' => 'required|email|max:150|min:5|unique:users,email',
      'password' => 'required|string',
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
    // if (array_key_exists('rc_code', $data)) {
    //   unset($data['rc_code']);
    // } else {
    //   unset($data['referer']);
    // }
    if (array_key_exists('referer', $data) && $data['referer'] !=( null|| "")) {
      $referer = User::select('id')->where('username', $data['referer'])->first();
      $data['referer'] = $referer->id;
    }else {
      unset($data['referer']);
    }
    $data['password'] = Hash::make($data['password']);
    return User::create($data);
  }
}
