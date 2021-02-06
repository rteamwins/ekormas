<?php

namespace App;

use Illuminate\Support\Facades\Log;

trait CalculateMatchingBonus
{
  /**
   * Check if User is ready for Bonus
   * @param float $amount.
   * @param Int $stage.
   * @param String $matching_type.
   *
   * @return void
   */
  public function calculate_matching_bonus($total_node_count)
  {

    Log::info('Calculating Matching Bonus For User: ' . $this->id . ' Node count: ' . $total_node_count);
    $stage_num = $this->has_any_stage_required_node($total_node_count);
    if ($stage_num !== 0) {
      Log::info('Matching Bonus Stage: ' . $stage_num);
      $stage_per = (5 * ((100 / $stage_num) / 100));
      Log::info('Matching Bonus Stage Percent: ' . $stage_per);
      $left_leg = User::withDepth()->find($this->children->first()->id);
      $right_leg = User::withDepth()->find($this->children->last()->id);
      Log::info($right_leg->depth + $stage_num);
      Log::info($left_leg->depth + $stage_num);

      $left_desc = static::withDepth()
        ->having('depth', '=', (($left_leg->depth + $stage_num) - 1))
        ->descendantsAndSelf($left_leg->id);
      $right_desc = static::withDepth()
        ->having('depth', '=', (($right_leg->depth + $stage_num) - 1))
        ->descendantsAndSelf($right_leg->id);

      $amount['left_amount'] = $left_desc->sum('membership_plan.fee');
      $amount['right_amount'] = $right_desc->sum('membership_plan.fee');
      Log::info('Left Amount: ' . $amount['left_amount']);
      Log::info('Right Amount: ' . $amount['right_amount']);
      $amount['left_amount'] *= ($stage_per / 100);
      $amount['right_amount'] *= ($stage_per / 100);
      Log::info('Left Amount Reduced: ' . $amount['left_amount']);
      Log::info('Right Amount Reduced: ' . $amount['right_amount']);
      $weak_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];
      Log::info('Weak Amount: ' . $weak_amount);
      if ($weak_amount > 0) {
        $this->give_stage_matching_bonus($weak_amount, $stage_num, 'stage');
      }

      $amount = [];
      if ($stage_num % 2 == 0) {
        Log::info('Matching Bonus Level: ' . $stage_num / 2);
        $left_leg = static::withDepth()->find($this->children->first()->id);
        $right_leg = static::withDepth()->find($this->children->last()->id);
        $left_desc = static::withDepth()
          ->having('depth', '>', ((($right_leg->depth + $stage_num) - 1) - 2))
          ->having('depth', '<', ((($right_leg->depth + $stage_num) - 1) + 1))
          ->descendantsAndSelf($left_leg->id);
        $right_desc = static::withDepth()
          ->having('depth', '>', ((($right_leg->depth + $stage_num) - 1) - 2))
          ->having('depth', '<', ((($right_leg->depth + $stage_num) - 1) + 1))
          ->descendantsAndSelf($right_leg->id);
        $amount['left_amount'] = $left_desc->sum('membership_plan.fee');
        $amount['right_amount'] = $right_desc->sum('membership_plan.fee');
        Log::info('Left Amount: ' . $amount['left_amount']);
        Log::info('Right Amount: ' . $amount['right_amount']);
        $amount['left_amount'] *= ($stage_per / 100);
        $amount['right_amount'] *= ($stage_per / 100);
        Log::info('Left Amount Reduced: ' . $amount['left_amount']);
        Log::info('Right Amount Reduced: ' . $amount['right_amount']);
        $weak_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];
        Log::info('Weak Amount: ' . $weak_amount);
        $weak_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];
        Log::info('Weak Amount: ' . $weak_amount);
        if ($weak_amount > 0) {
          $this->give_stage_matching_bonus($weak_amount, ($stage_num / 2), 'level');
        }
      }
    }
  }

  public function has_any_stage_required_node($has)
  {
    $i = array_search($has, $this->required_node_per_stage());
    if ($i === false) {
      return 0;
    } else {
      return $i + 1;
    }
  }

  public function required_node_per_stage()
  {
    $n = 1;
    while ($n <= 20) {
      $arry[] = $n == 1 ? 2 ** $n : ((2 ** $n) + (2 ** ($n - 1)));
      $n++;
    }
    return $arry;
  }
}
