<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  protected $with = ['method','payable'];



  protected $fillable = [
    'amount', 'status',
    'user_id','type'
  ];

  public function method()
  {
    return $this->morphTo();
  }

  public function payable()
  {
    return $this->morphTo();
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id',);
  }

  protected $dateFormat = 'Y-m-d H:i:s.u';
}
