<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'title', 'slug',
    'image', 'message',
  ];

  protected $dateFormat = 'Y-m-d H:i:s.u';

  public function orders()
  {
    return $this->hasMany(Order::class, 'order_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function (Post $model) {
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
    });
  }
}
