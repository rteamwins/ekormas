<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('product_id');
      $table->unsignedBigInteger('transaction_id')->default(null)->nullable();
      $table->integer('type');
      $table->integer('quantity');
      $table->decimal('total_amount', 14, 2);
      $table->decimal('discount_percent', 7, 4)->default(0);
      $table->string('status');
      $table->string('delivery_country');
      $table->string('delivery_state');
      $table->string('delivery_address');
      $table->timestamp('collected_at', 6)->nullable()->default(null);
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
    Schema::dropIfExists('orders');
  }
}
