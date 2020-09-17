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
    Schema::table('fundings', function (Blueprint $table) {
      $table->foreign('transaction_id')->references('id')->on('transactions');
    });

    Schema::table('trades', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('membership_plan_users', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('membership_plan_id')->references('id')->on('membership_plans');
      $table->foreign('transaction_id')->references('id')->on('transactions');
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
    });

    Schema::table('k_y_c_s', function (Blueprint $table) {
      $table->foreign('created_by')->references('id')->on('users');
      $table->foreign('used_by')->references('id')->on('users');
    });

    Schema::table('orders', function (Blueprint $table) {
      $table->foreign('product_id')->references('id')->on('products');
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('transaction_id')->references('id')->on('transactions');
    });

    Schema::table('product_carts', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('fundings', function (Blueprint $table) {
      $table->dropForeign('transactions_id');
    });

    Schema::table('trades', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });

    Schema::table('membership_plan_users', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('membership_plan_id');
      $table->dropForeign('transactions_id');
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
    });

    Schema::table('k_y_c_s', function (Blueprint $table) {
      $table->dropForeign('created_by');
      $table->dropForeign('used_by');
    });

    Schema::table('orders', function (Blueprint $table) {
      $table->dropForeign('product_id');
      $table->dropForeign('user_id');
      $table->dropForeign('transactions_id');
    });

    Schema::table('product_carts', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });
  }
}
