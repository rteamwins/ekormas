<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoTransactionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('crypto_transactions', function (Blueprint $table) {
      $table->id();
      $table->string('status');
      $table->string('currency');
      $table->string('hosted_url')->nullable()->default(null);
      $table->uuid('charge_id')->nullable()->default(null);
      $table->string('charge_code', 10)->nullable()->default(null);
      $table->string('system_wallet_address', 45)->nullable()->default(null);
      $table->string('unresolved_context')->nullable()->default(null);
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
    Schema::dropIfExists('crypto_transactions');
  }
}
