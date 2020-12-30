<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->foreign('membership_plan_id')->references('id')->on('membership_plans');
    });

    Schema::table('fundings', function (Blueprint $table) {
      $table->foreign('transaction_id')->references('id')->on('transactions');
    });

    Schema::table('trades', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('bonuses', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('transactions', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('withdraws', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('referals', function (Blueprint $table) {
      $table->foreign('referer_id')->references('id')->on('users');
      $table->foreign('referred_id')->references('id')->on('users');
      $table->foreign('bonus_id')->references('id')->on('bonuses');
    });

    Schema::table('k_y_c_s', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('used_by')->references('id')->on('users');
    });

    Schema::table('registration_credits', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('used_by')->references('id')->on('users');
    });

    Schema::table('product_carts', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('transaction_id')->references('id')->on('transactions');
    });

    Schema::table('profits', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('trade_id')->references('id')->on('trades');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign('membership_plan_id');
    });

    Schema::table('fundings', function (Blueprint $table) {
      $table->dropForeign('transactions_id');
    });

    Schema::table('trades', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });

    Schema::table('bonuses', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });
    
    Schema::table('transactions', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });

    Schema::table('withdraws', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });

    Schema::table('referals', function (Blueprint $table) {
      $table->dropForeign('referer_id');
      $table->dropForeign('referred_id');
      $table->dropForeign('bonus_id');
    });

    Schema::table('k_y_c_s', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('used_by');
    });

    Schema::table('registration_credits', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('used_by');
    });

    Schema::table('product_carts', function (Blueprint $table) {
      $table->dropForeign('transactions_id');
      $table->dropForeign('user_id');
    });

    Schema::table('profits', function (Blueprint $table) {
      $table->dropForeign('trade_id');
      $table->dropForeign('user_id');
    });
  }
}
