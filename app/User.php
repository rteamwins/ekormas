<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name', 'last_name', 'gender',
    'gender', 'investment_wallet', 'bounus_wallet',
    'profit_wallet', 'email', 'password', 'referer',
    'role', 'activated_at',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'activated_at' => 'datetime',
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

  public function kycs()
  {
    return $this->hasMany(KYC::class, 'created_by');
  }

  
}
