<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationCredit extends Model
{
  protected $fillable = [
    'code', 'status', 'plan',
    'amount', 'user_id', 'used_by',
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

  public function consumer()
  {
    return $this->belongsTo(User::class, 'used_by');
  }
}
