<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
  protected $fillable = [
    'products', 'type', 'total_amount',
    'status', 'user_id', 'delivery_country', 'delivery_state',
    'delivery_address', 'converted', 'collected_at',
  ];

  public function transaction()
  {
    return $this->hasOne(Transaction::class, 'payble');
  }

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [
    'collected' => 'DateTime',
    'converted' => 'Boolean',
  ];
}
