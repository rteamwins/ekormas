<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'code', 'title', 'slug', 'status',
    'category_id',
    'reward_level', 'delivery_duration',
    'amount', 'images', 'description',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class, 'order_id');
  }

  protected $cast = [
    'images' => 'Array',
  ];

  public function generate_code()
  {
    $code = strtoupper(Str::random(6));
    if (!static::where('id', '!=', $this->id)->where('code', $code)->withTrashed()->exists()) {
      return $code;
    } else {
      while (static::where('id', '!=', $this->id)->where('code', $code)->withTrashed()->exists()) {
        $code = strtoupper(Str::random(6));
      }
      return $code;
    }
  }

  public function getImagesAttribute($value)
  {
    $full_url = [];
    foreach (json_decode($value) as  $val) {
      $full_url[] = asset('images/product/' . $val);
    }
    return $full_url;
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function (Product $model) {
      $source = $model->title;
      $model_slug = Str::slug($source);
      if (!static::where('id', '!=', $model->id)->where('slug', $model_slug)->withTrashed()->exists()) {
        $model->slug = $model_slug;
      } else {
        $count = 1;
        while (static::where('id', '!=', $model->id)->where('slug', "{$model_slug}-" . $count)->withTrashed()->exists()) {
          $count++;
        }
        $model->slug = "{$model_slug}-" . $count;
      }
      $model->code = $model->generate_code();
    });
  }
}
