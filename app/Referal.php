<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
  protected $fillable = [
    'referer_id', 'referred_id', 'bonus_id'
  ];
  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [
    'released' => 'Boolean'
  ];

  public function bonus()
  {
    return $this->belongsTo(Bonus::class);
  }
}
