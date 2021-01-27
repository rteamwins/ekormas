<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationCreditsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('registration_credits', function (Blueprint $table) {
      $table->id();
      $table->string('code', 15)->unique();
      $table->string('status')->nullable()->default(null);
      $table->string('plan')->default('pearl');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('used_by')->nullable()->default(null);
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
    Schema::dropIfExists('registration_credits');
  }
}
