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
      $table->unsignedBigInteger('transaction_id')->default(null)->nullable();
      $table->unsignedBigInteger('user_id');
      $table->string('type');
      $table->decimal('total_amount', 14, 2);
      $table->string('status');
      $table->unsignedBigInteger('state_id');
      $table->unsignedBigInteger('lga_id');
      $table->char('country_code', 2);
      $table->text('address')->nullable();
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
    Schema::dropIfExists('orders');
  }
}
