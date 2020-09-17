<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'product_id','type','quantity','total_amount',
    'status','user_id','delivery_country','delivery_state',
    'delivery_address','invested','collected_at',
  ];

  public function investment()
  {
    $this->morphOne(Investment::class, 'investable');
  }

  public function transaction()
  {
    return $this->hasOne(OrderTransaction::class, 'order_id');
  }

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [
    'collected'=>'DateTime',
    'invested'=>'Boolean',
  ];
}
