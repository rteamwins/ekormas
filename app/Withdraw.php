<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
  protected $fillable = [
    'amount', 'fee', 'status',
    'destination_wallet_address',
    'user_id',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
