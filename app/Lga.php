<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lga extends Model
{

  const filterables = [
    'name', 'state', 'country',
  ];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'slug', 'country_code', 'state_code',
  ];

  public function users()
  {
    return $this->hasMany(User::class)->where('role', 'user');
  }

  public function agents()
  {
    return $this->hasMany(User::class)->where('role', 'agents');
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_id');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code', 'iso2');
  }
}
