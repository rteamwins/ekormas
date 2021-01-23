<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ordered_products', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('product_id');
      $table->timestamp('created_at', 6)->useCurrent();
      $table->timestamp('updated_at', 6)->useCurrent()->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('ordered_products');
  }
}
