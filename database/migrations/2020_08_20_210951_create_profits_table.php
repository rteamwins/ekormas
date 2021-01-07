<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('profits', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('trade_id');
      $table->unsignedBigInteger('user_id');
      $table->decimal('amount', 27, 16);
      $table->decimal('volume', 12, 4);
      $table->boolean('applied')->default(false);
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
    Schema::dropIfExists('profits');
  }
}
