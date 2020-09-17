<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  protected $with = ['transactable'];

  
  protected $fillable = [
    'amount', 'status',
    'user_id'
  ];

  public function transactable()
  {
    return $this->morphTo();
  }

  protected $dateFormat = 'Y-m-d H:i:s.u';
}
