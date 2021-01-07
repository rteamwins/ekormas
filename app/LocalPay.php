<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LocalPay extends Model
{

  protected $fillable = [
    'agent_id', 'amount', 'fee',
    'user_id', 'pop', 'bank_name',
    'status', 'account_name', 'account_number',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $hidden = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function agent()
  {
    return $this->belongsTo(User::class, 'agent_id');
  }

  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }

  public function getPopAttribute($value)
  {
    return isset($value)? asset('images/pop/' . $value) : null;
  }
}
