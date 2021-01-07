<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
  protected $fillable = [
    'fee', 'slug', 'name', 'min_trading_capital', 'max_trading_capital',
    'weekly_membership_percent', 'weekly_trading_percent', 'membership_cancellation_percent',
    'product_discount_percent', 'product_resale_percent', 'kyc_creation_percent',
    'referal_bonus_percent', 'level1_downline_upgrade_bonus_percent', 'status'
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = ['applied' => 'boolean'];


  public function user()
  {
    return $this->hasMany(User::class);
  }
}
