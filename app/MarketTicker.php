<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketTicker extends Model
{
  protected $fillable = [
    'date', 'open', 'high',
    'low', 'close', 'volume',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [
    'date' => 'datetime',
  ];
}
