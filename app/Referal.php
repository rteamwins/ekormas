<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
  protected $fillable = [
    'referer_id', 'referred_id', 'amount',
    'released',
  ];
  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [
    'released' => 'Boolean'
  ];


}
