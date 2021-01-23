<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentApplication extends Model
{
  protected $fillable = [
    'state_id', 'country_code', 'lga_id',
    'address', 'id_card', 'user_id', 'status',
    'transaction_id',
  ];

  public function transaction()
  {
    return $this->hasOne(Transaction::class, 'transaction_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code', 'iso2');
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_id');
  }

  public function lga()
  {
    return $this->belongsTo(Lga::class, 'lga_id');
  }

  public function getIdCardAttribute($value)
  {
    return $value !== NULL ? asset('images/agent_application/' . $value) : null;
  }
}
