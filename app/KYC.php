<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class KYC extends Model
{

  protected $fillable = [
    'address', 'code', 'amount', 'fee',
    'user_id', 'used_by',
    'status',
  ];

  protected $cast = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  protected $hidden = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function consumer()
  {
    return $this->belongsTo(User::class, 'used_by');
  }

  public function transaction()
  {
    return $this->morphOne(Transaction::class, 'method');
  }

  function crypto_rand_secure($min, $max)
  {
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
  }

  function getToken($length)
  {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
      $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max - 1)];
    }

    return $token;
  }

  public function generateAddress()
  {
    return str_replace("-", "", Str::uuid());
  }


  protected static function boot()
  {
    parent::boot();
    static::creating(function (KYC $model) {
      $address = $model->generateAddress();
      $model->address = $address;
      $model->code = $model->getToken(22);
    });
  }
}
