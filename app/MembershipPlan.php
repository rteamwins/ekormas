<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MembershipPlan extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'fee', 'slug', 'name', 'min_trading_capital', 'max_trading_capital',
    'weekly_membership_percent', 'weekly_trading_percent', 'membership_cancellation_percent',
    'product_discount_percent', 'product_resale_percent', 'kyc_creation_percent',
    'referal_bonus_percent', 'level1_downline_upgrade_bonus_percent', 'status', 'point_value'
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = ['applied' => 'boolean'];


  public function user()
  {
    return $this->hasMany(User::class);
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function (MembershipPlan $model) {
      $source = $model->title;
      $model_slug = Str::slug($source);
      if (!static::where('id', '!=', $model->id)->where('slug', $model_slug)->withTrashed()->exists()) {
        $model->slug = $model_slug;
      } else {
        $count = 1;
        while (static::where('id', '!=', $model->id)->where('slug', "{$model_slug}-" . $count)->withTrashed()->exists()) {
          $count++;
        }
        $model->slug = "{$model_slug}-" . $count;
      }
    });
  }
}
