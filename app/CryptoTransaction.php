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
    'recieving_wallet_address',
    'unresolved_context',
  ];

  public function transaction()
  {
    $this->morphOne(Transaction::class, 'transactable');
  }
  protected $dateFormat = 'Y-m-d H:i:s.u';
}
