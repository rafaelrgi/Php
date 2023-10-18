<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    if (!Schema::hasTable('users')) {
      Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('login')->unique();
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->boolean('is_admin');
        $table->rememberToken();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();
      });
    }

    DB::table('users')->insert([
      'name' => 'Admin',
      'login' => 'admin',
      'email' => 'admin@adm.com',
      'is_admin' => true,
      'password' => '$2y$10$Rxm2OQvI5kdu.ZczBWOh.OjXQVncdkCotX0owxbCyxwysan.39/Fy', //1234
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
