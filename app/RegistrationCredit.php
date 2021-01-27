<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RegistrationCredit extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'code', 'status', 'plan',
    'amount', 'user_id', 'used_by',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $cast = [];

  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function consumer()
  {
    return $this->belongsTo(User::class, 'used_by');
  }

  public function generateCode()
  {
    return strtoupper(Str::random(15));
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function (RegistrationCredit $model) {
      $code = $model->generateCode();
      if (!static::where('id', '!=', $model->id)->where('code', $code)->exists()) {
        $model->code = $code;
      } else {
        while (static::where('id', '!=', $model->id)->where('code', $code)->exists()) {
          $code = $model->generateCode();
        }
        $model->code = $code;
      }
    });
  }
}
