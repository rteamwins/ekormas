<?php

namespace App;


trait CalculateSalesPoint
{
  /**
   * Check if User is ready for Bonus
   * @param float $amount.
   * @param Int $stage.
   * @param String $matching_type.
   *
   * @return void
   */
  public function calculate_sales_bonus()
  {
    if ($this->children->count() < 2) {
    }
    $left_desc = static::withDepth()
      ->descendantsOf($this->children->first())->where('activated_at', now()->startOfDay());
    $right_desc = static::withDepth()
      ->descendantsOf($this->children->last())->where('activated_at', now()->startOfDay());
    $amount['left_amount'] = $left_desc->sum('membership_plan.point_value');
    $amount['right_amount'] = $right_desc->sum('membership_plan.point_value');
    $weak_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];
    if ($weak_amount > 0) {
      $this->give_active_sales_point(($weak_amount * 0.05));
    }
    $stong_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['right_amount'] : $amount['left_amount'];
    if ($stong_amount > 0) {
      $this->give_dormant_sales_point(($weak_amount / 2));
    }
  }
}
