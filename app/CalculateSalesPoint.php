<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
    Log::channel('point')->info("Calculating Daily Sale Bonus for User: " . $this->id . " Starting...");
    if ($this->children->count() < 2) {
      $left_desc = static::withDepth()
        ->descendantsOf($this->children->first());
      $amount['left_amount'] = $left_desc->sum(function ($user) {
        if (isset($user->activated_at) && Carbon::parse($user->activated_at)->isCurrentDay()) {
          return $user->membership_plan->point_value;
        }
      });
      $stong_amount = $amount['left_amount'];
      Log::channel('point')->info("Calculated Left Amount: " . $amount['left_amount'] . " Right Amount: 0");
      if (($stong_amount / 2) > 0) {
        $this->give_dormant_sales_point(($stong_amount / 2));
      }
    } else {
      $left_desc = static::withDepth()
        ->descendantsOf($this->children->first());
      $right_desc = static::withDepth()
        ->descendantsOf($this->children->last());
      $amount['left_amount'] = $left_desc->sum(function ($user) {
        if (isset($user->activated_at) && Carbon::parse($user->activated_at)->isCurrentDay()) {
          return $user->membership_plan->point_value;
        }
      });
      $amount['right_amount'] = $right_desc->sum(function ($user) {
        if (isset($user->activated_at) && Carbon::parse($user->activated_at)->isCurrentDay()) {
          return $user->membership_plan->point_value;
        }
      });
      $weak_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['left_amount'] : $amount['right_amount'];

      Log::channel('point')->info("Calculated Left Amount: " . $amount['left_amount'] . " Right Amount: " . $amount['right_amount']);
      if (($weak_amount * 0.05) > 0) {
        $this->give_active_sales_point(($weak_amount * 0.05));
      }
      $stong_amount = $amount['left_amount'] <= $amount['right_amount'] ? $amount['right_amount'] : $amount['left_amount'];
      if (($stong_amount / 2) > 0) {
        $this->give_dormant_sales_point(($stong_amount / 2));
      }
    }

    Log::channel('point')->info("Calculating Daily Sale Bonus for User: " . $this->id . " Completed");
  }
}
