<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedProducts extends Model
{
  protected $fillable = [
    'order_id', 'product_id', 'quantity',
  ];

  protected $cast = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $appends = ['sub_total'];

  protected $hidden = [];

  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function getSubTotalAttribute()
  {
    return ($this->product->amount * $this->quantity) ?? 0;
  }
}
