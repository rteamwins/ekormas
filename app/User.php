<?php

namespace App;

use App\CalculateMatchingBonus;
use App\GiveMatchingBonus;
use App\GiveReferalBonus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kalnoy\Nestedset\NodeTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
  use Notifiable,
    HasApiTokens,
    NodeTrait,
    GiveReferalBonus,
    GiveMatchingBonus,
    GiveActiveSalesPoint,
    GiveDormantSalesPoint,
    CalculateMatchingBonus,
    CalculateSalesPoint;


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'parent_id', 'placement_id', 'name',
    'active_points', 'dormant_points',
    'wallet', 'bonus', 'email', 'password',
    'referer', 'role', 'activated_at', 'phone',
    'trading_capital', 'membership_plan_id',
    'last_profit_at', 'points', 'username',
    'country_code',
    'state_id',
    'lga_id',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $with = ['membership_plan'];
  // protected $withCount = ['downlines'];
  // protected $withCount = ['registration_credits'];
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

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code');
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_id');
  }

  public function lga()
  {
    return $this->belongsTo(Lga::class, 'lga_id');
  }

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

  public function downlines()
  {
    return $this->hasMany(User::class, 'referer');
  }


  public function created_kycs()
  {
    return $this->hasMany(KYC::class);
  }

  public function avail_created_kycs()
  {
    return $this->hasMany(KYC::class)->whereUsedBy(null);
  }

  public function avail_created_kycs_sum()
  {
    return $this->avail_created_kycs()->sum('amount');
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

  public function alerts()
  {
    return Alert::whereStatus('active')->get();
  }

  public function generate_placement_id()
  {
    return random_int(1000000000, 9999999999);
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function (User $model) {
      $pid = $model->generate_placement_id();
      while (User::where('placement_id', $pid)->exists()) {
        $pid = $model->generate_placement_id();
      }
      $model->placement_id = $pid;
    });
  }
}
