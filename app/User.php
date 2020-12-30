<?php

namespace App;

use App\Http\Controllers\HomeController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name', 'last_name', 'username', 'phone', 'gender',
    'gender', 'wallet', 'bounus', 'email', 'password', 'referer',
    'role', 'activated_at', 'trading_capital', 'membership_plan_id',
    'last_profit_at', 'points',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];


  protected $appends = ['available_wallet'];
  protected $withCount = ['registration_credits', 'referals'];
  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'activated_at' => 'datetime',
    'last_profit_at' => 'datetime',
  ];


  protected $dateFormat = 'Y-m-d H:i:s.u';


  public function transactions()
  {
    return $this->hasMany(Transaction::class, 'user_id');
  }

  public function trades()
  {
    return $this->hasMany(Trade::class, 'user_id');
  }

  public function orders()
  {
    return $this->hasMany(Order::class, 'user_id');
  }

  public function product_cart()
  {
    return $this->hasOne(ProductCart::class, 'user_id');
  }

  public function referals()
  {
    return $this->hasMany(Referal::class, 'referer_id');
  }

  public function referer()
  {
    return $this->hasOne(User::class, 'referer');
  }

  public function created_kycs()
  {
    return $this->hasMany(KYC::class);
  }

  public function consumed_kycs()
  {
    return $this->hasMany(KYC::class, 'used_by');
  }

  public function registration_credits()
  {
    return $this->hasMany(RegistrationCredit::class);
  }

  public function membership_plan()
  {
    return $this->belongsTo(MembershipPlan::class);
  }

  public function getAvailableWalletAttribute()
  {
    return $this->wallet - $this->membership_plan->fee;
  }
}
