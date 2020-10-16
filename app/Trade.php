<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'amount', 'earning', 'user_id',
    'profit_percent', 'completed',
    'method', 'closing_at',
  ];


  protected $dateFormat = 'Y-m-d H:i:s.u';
  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $cast = [
    'completed' => 'boolean',
    'closing_at' => 'datetime',
  ];


  protected $dates = [
    'closing_at',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function profits()
  {
    return $this->hasMany(Profit::class, 'trade_id');
  }
}
