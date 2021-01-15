<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
  protected $fillable = [
    'user_id', 'amount', 'status', 'type', 'tag',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [];


  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
