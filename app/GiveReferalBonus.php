<?php

namespace App;

use App\Bonus;
use App\Transaction;
use Illuminate\Support\Facades\Log;

trait GiveReferalBonus
{
  public function give_ancestor_referal_bonus()
  {
    $percent = [4, 2, 1, 0.5, 0.25];
    $ancestors = User::latest()->limit(5)->ancestorsOf($this->id);
    $referer =  User::where('id', $this->referer)->first();
    $user =  User::with('membership_plan')->where('id', $this->id)->first();

    Log::channel('bonus')->info('Giving Direct Referal Bonus to user: ' . $referer->id);
    $new_trx = new Transaction();
    Log::channel('bonus')->info("plan_fee: " . $user->membership_plan->fee ?? 0);
    $new_trx->amount = (($user->membership_plan->fee ?? 0) * 0.10);
    $new_trx->status = 'created';
    $new_trx->type = 'bonus';
    $new_trx->user_id = $referer->id;

    $new_bonus_trx = new Bonus();
    $new_bonus_trx->user_id = $user->referer;
    $new_bonus_trx->amount = (($user->membership_plan->fee ?? 0) * 0.10);
    $new_bonus_trx->status = 'created';
    $new_bonus_trx->type = 'referal_direct';

    $new_bonus_trx->save();
    $new_bonus_trx->transaction()->save($new_trx);
    $new_trx->status = 'completed';
    $new_trx->update();
    $referer->bonus += (($user->membership_plan->fee ?? 0) * 0.10);
    $referer->update();
    Log::channel('bonus')->info('Giving Direct Referal Bonus: $' . $new_bonus_trx->amount . ' to user: ' . $referer->id . " completed");
    Log::channel('bonus')->info('Skipping Indirect Referal');

    foreach ($ancestors as $key => $ancestor) {
      Log::channel('bonus')->info('Giving Ancestor Referal Bonus to user: ' . $ancestor->id);
      $new_trx = new Transaction();
      $new_trx->amount = (($user->membership_plan->fee ?? 0) * ($percent[$key] / 100));
      $new_trx->status = 'created';
      $new_trx->type = 'bonus';
      $new_trx->user_id = $ancestor->id;

      $new_bonus_trx = new Bonus();
      $new_bonus_trx->user_id = $ancestor->id;
      $new_bonus_trx->amount = (($user->membership_plan->fee ?? 0) * ($percent[$key] / 100));
      $new_bonus_trx->status = 'created';

      Log::channel('bonus')->info('Giving Ancestor Referal Bonus to user: ' . $ancestor->id);
      $new_bonus_trx->type = 'referal_indirect';

      $new_bonus_trx->save();
      $new_bonus_trx->transaction()->save($new_trx);
      $new_trx->status = 'completed';
      $new_trx->update();
      $ancestor->bonus += (($user->membership_plan->fee ?? 0) * ($percent[$key] / 100));
      $ancestor->update();
      Log::channel('bonus')->info('Giving Ancestor Referal Bonus: $' . $new_bonus_trx->amount . ' to user: ' . $ancestor->id . " completed");
    }
  }

  public function check_for_bonus_eligible_ancestors($user)
  {
    Log::channel('bonus')->info('Check Potential Matching Bonus Ancestor...');
    $ancestors = User::defaultOrder()->with(['membership_plan'])
      ->ancestorsOf($user->id, ['id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'phone', 'membership_plan_id', 'created_at', 'activated_at']);
    foreach ($ancestors as $ancestor) {
      $ancestor_directline_count = $ancestor->children->count();
      $leg_count[$ancestor->username]['name'] = $ancestor->name;
      if ($ancestor_directline_count > 0) {
        if ($ancestor_directline_count == 2) {
          $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;

          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $left_desc = User::descendantsOf($ancestor->children->first());
          $leg_count[$ancestor->username]['left'] += $left_desc->count();
          $leg_count[$ancestor->username]['left_amount'] += $left_desc->sum('membership_plan.fee');

          $leg_count[$ancestor->username]['right_amount'] = $ancestor->children->last()->membership_plan->fee ?? 0;
          $right_desc = User::descendantsOf($ancestor->children->last());
          $leg_count[$ancestor->username]['right'] += $right_desc->count();
          $leg_count[$ancestor->username]['right_amount'] += $right_desc->sum('membership_plan.fee');

          if ($leg_count[$ancestor->username]['left'] == $leg_count[$ancestor->username]['right']) {
            if ($this->has_any_stage_required_node($leg_count[$ancestor->username]['left'] + $leg_count[$ancestor->username]['right'])) {
              Log::channel('bonus')->info('Eligible Matching Bonus Ancestor found');
              $ancestor->calculate_matching_bonus($leg_count[$ancestor->username]['left'] + $leg_count[$ancestor->username]['right']);
            }
          }
        } else {
          $leg_count[$ancestor->username]['left'] = 1;
          $leg_count[$ancestor->username]['right'] = 0;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = 0;
        }
      } else {
        $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 0;
        $leg_count[$ancestor->username]['left_amount'] = $leg_count[$ancestor->username]['right_amount'] = 0;
      }
    }
    Log::channel('bonus')->info('Check Ended');
  }
}
