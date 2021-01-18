<?php

namespace App;

use App\point;
use App\Transaction;

trait GiveActiveSalesPoint
{
  /**
   * Boot the Set Slug Attribute trait for the model.
   *
   * @return void
   */
  public function give_active_sales_point($amount)
  {
    // $parent =  static::where('id', $model->parent_id)->first();
    $new_trx = new Transaction();
    $new_trx->amount =  $amount;
    $new_trx->status = 'created';
    $new_trx->type = 'active_point';
    $new_trx->user_id = $this->id;

    $new_point_trx = new Point();
    $new_point_trx->user_id = $this->id;
    $new_point_trx->amount = $amount;
    $new_point_trx->status = 'created';
    $new_point_trx->type = 'daily_sales_active';
    $new_point_trx->save();
    $new_point_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $this->active_point += $new_trx->amount;
    $this->update();
  }
}
