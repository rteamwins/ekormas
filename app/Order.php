<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'user_id', 'transaction_id', 'type',
    'total_amount', 'status', 'traded',
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
}
