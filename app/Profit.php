<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
  protected $fillable = [
    'amount', 'trade_id','user_id'
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function trade()
  {
    return $this->belongsTo(Trade::class, 'trade_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
