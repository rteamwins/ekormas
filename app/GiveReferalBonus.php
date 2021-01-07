<?php

namespace App;

use App\Bonus;
use App\Transaction;

trait GiveReferalBonus
{
  /**
   * Boot the Set Slug Attribute trait for the model.
   *
   * @return void
   */
  public function give_referal_bonus()
  {
    $referer =  static::where('id', $this->referer)->first();
    // $parent =  static::where('id', $model->parent_id)->first();
    $new_trx = new Transaction();
    $new_trx->amount =  (($this->membership_plan->fee ?? 0) * 0.10);
    $new_trx->status = 'created';
    $new_trx->type = 'bonus';
    $new_trx->user_id = $referer->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $referer->id;
    $new_bonus_trx->amount = (($this->membership_plan->fee ?? 0) * 0.10);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'referal_direct';
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $referer->bonus += $new_trx->amount;
    $referer->update();
  }
}
