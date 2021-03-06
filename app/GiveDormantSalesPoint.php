<?php

namespace App;

use App\Point;
use App\Transaction;
use Illuminate\Support\Facades\Log;

trait GiveDormantSalesPoint
{
  /**
   * Boot the Set Slug Attribute trait for the model.
   *
   * @return void
   */
  public function give_dormant_sales_point($amount)
  {
    Log::channel('point')->info("Preparing Daily Dormant Sale Bonus for User: " . $this->id . " Starting...");
    // $parent =  static::where('id', $model->parent_id)->first();
    $new_trx = new Transaction();
    $new_trx->amount =  $amount;
    $new_trx->status = 'created';
    $new_trx->type = 'dormant_point';
    $new_trx->user_id = $this->id;

    $new_point_trx = new Point();
    $new_point_trx->user_id = $this->id;
    $new_point_trx->amount = $amount;
    $new_point_trx->status = 'created';
    $new_point_trx->type = 'daily_sales_dormant_strong_leg';
    $new_point_trx->save();
    $new_point_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $this->dormant_points += $new_trx->amount;
    $this->update();
    Log::channel('point')->info("Awarded Daily Dormant Sale Bonus: " . $amount . "DPV to User: " . $this->id . " Starting...");
    Log::channel('point')->info("Preparing Daily Dormant Sale Bonus for User: " . $this->id . " Completed");
  }
}
