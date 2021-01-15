<?php

namespace App;

use App\Bonus;
use App\Transaction;

trait GiveSalesBonus
{
  /**
   * Boot the Set Slug Attribute trait for the model.
   *
   * @return void
   */
  public function give_sales_bonus($amount)
  {
    // $parent =  static::where('id', $model->parent_id)->first();
    $new_trx = new Transaction();
    $new_trx->amount =  $amount;
    $new_trx->status = 'created';
    $new_trx->type = 'bonus';
    $new_trx->user_id = $this->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $this->id;
    $new_bonus_trx->amount = $amount;
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'direct_sales';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $this->bonus += $new_trx->amount;
    $this->update();
  }
}
