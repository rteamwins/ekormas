<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationCredit extends Model
{
  protected $fillable = [
    'code', 'status', 'plan', 'created_by',
    'amount', 'created_by', 'used_by',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [];

  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }
}
