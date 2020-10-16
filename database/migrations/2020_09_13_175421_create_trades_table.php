<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('trades', function (Blueprint $table) {
      $table->id();
      $table->decimal('amount', 14, 2)->default(0);
      $table->decimal('earning', 21, 9)->default(0);
      $table->unsignedBigInteger('user_id');
      $table->decimal('profit_percent', 7, 4);
      $table->boolean('completed');
      $table->string('method', 15)->default('manual');
      $table->timestamp('closing_at', 6)->nullable()->default(null);
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
    Schema::dropIfExists('trades');
  }
}
