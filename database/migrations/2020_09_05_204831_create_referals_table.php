<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferalsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('referals', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('referer_id');
      $table->unsignedBigInteger('referred_id');
      $table->unsignedBigInteger('bonus_id');
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
    Schema::dropIfExists('referals');
  }
}
