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
    if (!Schema::hasTable('reservas')) {
      Schema::create('reservas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('livro_id')->nullable();
        $table->unsignedBigInteger('aluno_id')->nullable();
        $table->unsignedBigInteger('emprestimo_id')->nullable();
        $table->date('data');
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();

        $table->foreign('aluno_id')->references('id')->on('alunos')->constrained();
        $table->foreign('livro_id')->references('id')->on('livros')->constrained();
        $table->foreign('emprestimo_id')->references('id')->on('emprestimo')->constrained();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('reservas');
  }
};
