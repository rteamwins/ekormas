<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentApplicationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('agent_applications', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('transaction_id')->nullable()->default(null);
      $table->char('country_code', 2)->nullable()->default(null);
      $table->unsignedBigInteger('state_id')->nullable()->default(null);
      $table->unsignedBigInteger('lga_id')->nullable()->default(null);
      $table->string('status')->default('pending');
      $table->string('id_card');
      $table->string('address');
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
    Schema::dropIfExists('agent_applications');
  }
}
