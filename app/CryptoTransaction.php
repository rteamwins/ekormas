<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
  protected $fillable = [
    'status',
    'investment_id',
    'charge_id',
    'charge_code',
    'system_wallet_address',
    'unresolved_context',
  ];

  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }
  protected $dateFormat = 'Y-m-d H:i:s.u';
}
