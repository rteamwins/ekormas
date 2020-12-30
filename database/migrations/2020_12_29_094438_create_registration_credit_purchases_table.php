<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationCreditPurchasesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('registration_credit_purchases', function (Blueprint $table) {
      $table->id();
      $table->string('status');
      $table->text('package');
      $table->text('quantity');
      $table->decimal('amount', 14, 2);
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('transaction_id');
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
    Schema::dropIfExists('registration_credit_purchases');
  }
}
