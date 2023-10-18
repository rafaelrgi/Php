<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    if (!Schema::hasTable('livros')) {
      Schema::create('livros', function (Blueprint $table) {
        $table->id();
        $table->char('isbn', 13);
        $table->string('titulo', 64);
        $table->string('autor', 64);
        $table->string('editora', 64);
        $table->integer('ano')->nullable();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('livros');
  }
};
