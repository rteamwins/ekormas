<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

  const filterables = [
    'name', 'country',
  ];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'name', 'slug', 'country_code', 'code',
  ];


  public function users()
  {
    return $this->hasMany(User::class)->where('role', 'user');
  }

  public function agents()
  {
    return $this->hasMany(User::class)->where('role', 'agents');
  }

  public function lgas()
  {
    return $this->hasMany(Lga::class, 'state_id');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code');
  }
}
