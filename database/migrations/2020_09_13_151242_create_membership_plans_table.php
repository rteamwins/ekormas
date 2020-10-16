<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPlansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('membership_plans', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->string('status', 25)->default('created');
      $table->decimal('fee', 14, 2)->default(0);
      $table->decimal('min_trading_capital', 14, 2)->default(0);
      $table->decimal('max_trading_capital', 14, 2)->default(0);
      $table->decimal('weekly_membership_percent', 7, 4)->default(0);
      $table->decimal('weekly_trading_percent', 7, 4)->default(0);
      $table->decimal('membership_cancellation_percent', 7, 4)->default(0);
      $table->decimal('product_discount_percent', 7, 4)->default(0);
      $table->decimal('product_resale_percent', 7, 4)->default(0);
      $table->decimal('kyc_creation_percent', 7, 4)->default(0);
      $table->decimal('referal_bonus_percent', 7, 4)->default(0);
      $table->decimal('level1_downline_upgrade_bonus_percent', 7, 4)->default(0);
      $table->timestamp('created_at', 6)->nullable()->default(null);
      $table->timestamp('updated_at', 6)->nullable()->default(null);
      $table->timestamp('deleted_at', 6)->nullable()->default(null);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('membership_plans');
  }
}
