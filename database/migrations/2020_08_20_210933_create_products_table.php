<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('code', 6);
      $table->string('title');
      $table->string('status');
      $table->string('slug')->unique();
      $table->decimal('amount', 14, 2);
      $table->integer('reward_level')->default(1); // [0, 1, 2, 3, 4, 5, 6, 7, 8, 9])
      $table->integer('delivery_duration');
      $table->text('images')->nullable();
      $table->text('description')->nullable();
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
    Schema::dropIfExists('products');
  }
}
