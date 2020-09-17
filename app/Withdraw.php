<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
  protected $fillable = [
    'amount', 'status', 'type',
    'destination_wallet_address',
    'user_id', 'investment_id',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function investment()
  {
    return $this->belongsTo(investment::class, 'investment_id');
  }

}
