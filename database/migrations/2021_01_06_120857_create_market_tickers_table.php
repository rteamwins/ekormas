<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketTickersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('market_tickers', function (Blueprint $table) {
      $table->id();
      $table->timestamp('date', 6)->useCurrent();
      $table->decimal('open', 10, 2);
      $table->decimal('high', 10, 2);
      $table->decimal('low', 10, 2);
      $table->decimal('close', 10, 2);
      $table->unsignedBigInteger('volume');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('market_tickers');
  }
}
