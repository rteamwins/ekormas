<?php

namespace App;

use App\Bonus;
use App\Transaction;
use Illuminate\Support\Facades\Log;

trait GiveReferalBonus
{
  /**
   * Boot the Set Slug Attribute trait for the model.
   *
   * @return void
   */
  public function give_referal_bonus()
  {
    // Log::info('Giving Referal Bonus to: ' . $this->referer);
    // $referer =  static::where('id', $this->referer)->first();
    // // $parent =  static::where('id', $model->parent_id)->first();
    // $new_trx = new Transaction();
    // $new_trx->amount =  (($this->membership_plan->fee ?? 0) * 0.10);
    // $new_trx->status = 'created';
    // $new_trx->type = 'bonus';
    // $new_trx->user_id = $referer->id;

    // $new_bonus_trx = new Bonus();
    // $new_bonus_trx->user_id = $referer->id;
    // $new_bonus_trx->amount = (($this->membership_plan->fee ?? 0) * 0.10);
    // $new_bonus_trx->status = 'created';
    // $new_bonus_trx->type = 'referal_direct';
    // $new_bonus_trx->save();
    // $new_bonus_trx->transaction()->save($new_trx);
    // $new_trx->status = 'completed';
    // $new_trx->update();
    // $referer->bonus += $new_trx->amount;
    // $referer->update();
    // $this->give_ancestor_referal_bonus();
    // Log::info('Giving Referal Bonus to: ' . $this->referer . "completed");
  }

  public function give_ancestor_referal_bonus()
  {
    $percent = [10, 4, 2, 1, 0.5, 0.25];
    $ancestors = User::latest()->limit(5)->ancestorsOf($this->id);

    foreach ($ancestors as $key => $ancestor) {
      Log::info('Giving Ancestor Referal Bonus to user: ' . $ancestor->id);
      $new_trx = new Transaction();
      $new_trx->amount = (($this->membership_plan->fee ?? 0) * ($percent[$key] / 100));
      $new_trx->status = 'created';
      $new_trx->type = 'bonus';
      $new_trx->user_id = $ancestor->id;

      $new_bonus_trx = new Bonus();
      $new_bonus_trx->user_id = $ancestor->id;
      $new_bonus_trx->amount = (($this->membership_plan->fee ?? 0) * ($percent[$key] / 100));
      $new_bonus_trx->status = 'created';
      if ($key == 0) {
        Log::info('Giving Direct Referal Bonus to user: ' . $ancestor->id);
        $new_bonus_trx->type = 'referal_direct';
      } else {
        Log::info('Giving Ancestor Referal Bonus to user: ' . $ancestor->id);
        $new_bonus_trx->type = 'referal_indirect';
      }
      $new_bonus_trx->save();
      $new_bonus_trx->transaction()->save($new_trx);
      $new_trx->status = 'completed';
      $new_trx->update();
      $ancestor->bonus += $new_trx->amount;
      $ancestor->update();
      Log::info('Giving Ancestor Referal Bonus: $' . $new_bonus_trx->amount . ' to user: ' . $ancestor->id . " completed");
    }
  }

  public function check_for_bonus_eligible_ancestors($user)
  {
    $ancestors = User::defaultOrder()->with(['membership_plan:id,fee,name'])
      ->ancestorsOf($user->id, ['id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'phone', 'membership_plan_id', 'created_at', 'activated_at']);
    foreach ($ancestors as $ancestor) {
      $ancestor_directline_count = $ancestor->children->count();
      $leg_count[$ancestor->username]['name'] = $ancestor->name;
      if ($ancestor_directline_count > 0) {
        if ($ancestor_directline_count == 2) {
          $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = $ancestor->children->last()->membership_plan->fee ?? 0;
        } else {
          $leg_count[$ancestor->username]['left'] = 1;
          $leg_count[$ancestor->username]['right'] = 0;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = 0;
        }
      } else {
        $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
        $leg_count[$ancestor->username]['left_amount'] = $leg_count[$ancestor->username]['right_amount'] = 0;
      }
      $left_desc = User::descendantsOf($ancestor->children->first());
      $right_desc = User::descendantsOf($ancestor->children->last());

      $leg_count[$ancestor->username]['left'] += $left_desc->count();
      $leg_count[$ancestor->username]['right'] += $right_desc->count();

      $leg_count[$ancestor->username]['left_amount'] += $left_desc->sum('membership_plan.fee');
      $leg_count[$ancestor->username]['right_amount'] += $right_desc->sum('membership_plan.fee');
      if ($leg_count[$ancestor->username]['left'] == $leg_count[$ancestor->username]['right']) {
        $ancestor->calculate_matching_bonus($leg_count[$ancestor->username]['left'] + $leg_count[$ancestor->username]['right']);
      }
    }
  }
}
