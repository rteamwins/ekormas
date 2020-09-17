<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
  protected $fillable = [
    'amount', 'status','investment_id',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function investment()
  {
    return $this->belongsTo(investment::class, 'investment_id');
  }
}
