<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'title', 'slug', 'amount',
    'reward_level', 'images', 'avail_qty',
    'sold_qty', 'description',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function orders()
  {
    return $this->hasMany(Order::class, 'order_id');
  }

  protected $cast = [
    'images' => 'Array',
  ];
}
