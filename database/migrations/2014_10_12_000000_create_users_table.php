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
      $table->string('first_name');
      $table->string('last_name');
      $table->string('username')->unique();
      $table->string('phone')->unique();
      $table->enum('gender', ['male', 'female']);
      $table->decimal('wallet', 14, 2)->default(0);
      $table->decimal('points')->default(0);
      $table->decimal('trading_capital', 14, 2)->default(0);
      $table->decimal('bonus', 14, 2)->default(0);
      $table->enum('role', ['user', 'agent', 'admin', 'super_admin'])->default('user');
      $table->unsignedBigInteger('referer')->nullable()->default(null);
      $table->string('email')->unique();
      $table->string('password');
      $table->rememberToken();
      $table->unsignedBigInteger('membership_plan_id')->nullable()->default(null);
      $table->timestamp('last_profit_at', 6)->nullable()->default(null);
      $table->timestamp('email_verified_at')->nullable();
      $table->timestamp('activated_at', 6)->nullable()->default(null);
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
