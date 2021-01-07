<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyRegistrationPayment
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::check() && Auth::user()->membership_plan_id == null) {
      return redirect()->route('choose_reg_plan');
    }
    return $next($request);
  }
}
