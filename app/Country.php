<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'iso2', 'iso3', 'name',
    'slug', 'enabled', 'emoji', 'emojiU',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'enabled' => 'boolean',
  ];


  public function users()
  {
    return $this->hasMany(User::class, 'country_code')->where('role', 'user');
  }

  public function agents()
  {
    return $this->hasMany(User::class, 'country_code')->where('role', 'agents');
  }


  public function states()
  {
    return $this->hasMany(State::class, 'country_code');
  }

  public function lgas()
  {
    return $this->hasMany(Lga::class, 'country_code');
  }
}
