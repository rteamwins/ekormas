<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationCreditPurchase extends Model
{

  private const RC_PACKAGES = [
    'pearl' => 130,
    'ruby' => 310,
    'gold' => 610,
    'sapphire' => 1210,
    'emerald' => 3160,
    'diamond' => 6010,
  ];
  protected $fillable = [
    'status', 'package', 'quantity',
    'amount', 'user_id', 'transaction_id',
  ];

  public function agent()
  {
    $this->belongsTo(User::class);
  }

  public function transaction()
  {
    return $this->hasOne(Transaction::class, 'transaction_id');
  }



  protected static function boot()
  {
    parent::boot();
    static::saving(function (RegistrationCreditPurchase $model) {
      $model->attributes['amount'] = ($model->getAttribute('quantity') * self::RC_PACKAGES[$model->getAttribute('package')]);
    });
  }
}
