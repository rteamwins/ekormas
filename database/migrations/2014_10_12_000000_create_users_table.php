<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->nestedSet();
      $table->unsignedBigInteger('placement_id');
      $table->unsignedBigInteger('membership_plan_id')->nullable()->default(null);
      $table->unsignedBigInteger('referer')->nullable()->default(null);
      $table->string('name');
      $table->string('username')->unique();
      $table->string('phone')->unique();
      $table->decimal('wallet', 14, 2)->default(0);
      $table->decimal('active_points')->default(0);
      $table->decimal('dormant_points')->default(0);
      $table->decimal('last_award_point')->default(0);
      $table->decimal('trading_capital', 14, 2)->default(0);
      $table->decimal('bonus', 14, 2)->default(0);
      $table->string('address')->nullable()->default(null);
      $table->char('country_code', 2)->nullable()->default(null);
      $table->unsignedBigInteger('state_id')->nullable()->default(null);
      $table->unsignedBigInteger('lga_id')->nullable()->default(null);
      $table->string('role')->default('buyer');
      $table->string('email')->unique();
      $table->string('password');
      $table->rememberToken();
      $table->timestamp('last_profit_at', 6)->nullable()->default(null);
      $table->timestamp('email_verified_at')->nullable();
      $table->timestamp('activated_at', 6)->nullable()->default(null);
      $table->timestamp('last_login', 6)->nullable()->default(null);
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
    Schema::dropIfExists('users');
  }
}
