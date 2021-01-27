<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'user_id', 'transaction_id', 'type',
    'status', 'traded', 'code',
    'country_code', 'address', 'state_id',
    'trade_id', 'collected_at', 'lga_id'
  ];

  protected $cast = [
    'collected_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $with = ['ordered_products', 'country', 'state', 'lga',];

  protected $appends = ['total_amount'];

  protected $hidden = [];

  public function transaction()
  {
    return $this->hasOne(Transaction::class, 'transaction_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function ordered_products()
  {
    return $this->hasMany(OrderedProducts::class);
  }

  public function getTotalAmountAttribute()
  {
    return $this->ordered_products->sum('sub_total');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code','iso2');
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_id');
  }

  public function lga()
  {
    return $this->belongsTo(Lga::class, 'lga_id');
  }
  public function generateCode()
  {
    return strtoupper(Str::random(10));
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function (Order $model) {
      $code = $model->generateCode();
      if (!static::where('id', '!=', $model->id)->where('code', $code)->exists()) {
        $model->code = $code;
      } else {
        while (static::where('id', '!=', $model->id)->where('code', $code)->exists()) {
          $code = $model->generateCode();
        }
        $model->code = $code;
      }
    });
  }
}
