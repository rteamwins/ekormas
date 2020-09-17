<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funding extends Model
{
  protected $fillable = [
    'amount',
    'status',
    'user_id',
    'transaction_id',
  ];

  public function transaction()
  {
    return $this->hasOne(Transaction::class, 'transaction_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  protected $dateFormat = 'Y-m-d H:i:s.u';
}
