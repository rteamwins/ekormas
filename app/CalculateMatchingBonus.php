<?php

namespace App;


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

    $stage_num = $this->has_any_stage_required_node($total_node_count);
    if ($stage_num !== 0) {
      $stage_per = (5 * ((100 / $stage_num) / 100));
      $left_desc = static::withDepth()
        ->having('depth', '=', $stage_num)
        ->descendantsOf($this->children->first());
      $right_desc = static::withDepth()
        ->having('depth', '=', $stage_num)
        ->descendantsOf($this->children->last());
      $amount['left_amount'] = $left_desc->sum('membership_plan.fee') * $stage_per;
      $amount['right_amount'] = $right_desc->sum('membership_plan.fee') * $stage_per;
      $weak_amount = $amount['left_amount'] < $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];
      $this->give_stage_matching_bonus($weak_amount, $stage_num, 'stage');

      if (2 % $stage_num == 0) {
        $left_desc = static::withDepth()
          ->having('depth', '>', ($stage_num - 2))
          ->having('depth', '<', ($stage_num + 1))
          ->descendantsOf($this->children->first());
        $right_desc = static::withDepth()
          ->having('depth', '>', ($stage_num - 2))
          ->having('depth', '<', ($stage_num + 1))
          ->descendantsOf($this->children->last());
        $amount['left_amount'] = $left_desc->sum('membership_plan.fee') * $stage_per;
        $amount['right_amount'] = $right_desc->sum('membership_plan.fee') * $stage_per;
        $weak_amount = $amount['left_amount'] < $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];
        $this->give_stage_matching_bonus($weak_amount, $stage_num, 'level');
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
