<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{

  protected $fillable = [
    'code', 'amount',
    'source', 'created_by',
    'used_by', 'status',
  ];

  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }
  protected $dateFormat = 'Y-m-d H:i:s.u';
}
