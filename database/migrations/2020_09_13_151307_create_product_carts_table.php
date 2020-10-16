<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCartsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_carts', function (Blueprint $table) {
      $table->id();
      $table->text('products')->nullable();
      $table->unsignedBigInteger('transaction_id')->default(null)->nullable();
      $table->unsignedBigInteger('user_id');
      $table->integer('type');
      $table->decimal('total_amount', 14, 2);
      $table->string('status');
      $table->string('delivery_country');
      $table->string('delivery_state');
      $table->text('delivery_address')->nullable();
      $table->boolean('traded')->default(false);
      $table->unsignedBigInteger('trade_id')->default(null)->nullable();
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
    Schema::dropIfExists('product_carts');
  }
}
