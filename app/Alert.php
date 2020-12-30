<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
  protected $fillable = [
    'message', 'status',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [];
}
