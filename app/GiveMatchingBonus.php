<?php

namespace App;

use App\Bonus;
use App\Transaction;
use Illuminate\Support\Facades\Log;

trait GiveMatchingBonus
{
  /**
   * Award bonus to user
   * @param float $amount.
   * @param Int $stage.
   * @param String $matching_type.
   *
   * @return void
   */
  public function give_stage_matching_bonus($amount, $stage, $matching_type)
  {
    Log::info('Awarding Matching Bonus For User: ' . $this->id);
    $new_trx = new Transaction();
    $new_trx->amount =  $amount;
    $new_trx->status = 'created';
    $new_trx->type = 'bonus';
    $new_trx->user_id = $this->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $this->id;
    $new_bonus_trx->amount = $amount;
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = "{$matching_type}_{$stage}_matching";
    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $this->bonus += $new_trx->amount;
    $this->update();
    Log::info('Awarding User: ' . $this->id . " {$matching_type} {$stage} " . " Matching Bonus: " . $amount . "Completed");
  }
}
