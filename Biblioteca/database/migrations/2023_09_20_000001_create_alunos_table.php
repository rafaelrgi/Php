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
    if (!Schema::hasTable('alunos')) {
      Schema::create('alunos', function (Blueprint $table) {
        $table->id();
        $table->string('matricula', 16);
        $table->string('cpf', 11);
        $table->string('nome', 64);
        $table->date('nascimento')->nullable();
        $table->char('genero', 1)->nullable();
        $table->string('fone', 16)->nullable();
        $table->string('email', 64)->nullable();
        $table->string('numero', 16)->nullable();
        $table->unsignedBigInteger('cep_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();

        $table->index('matricula');
        $table->foreign('cep_id')->references('id')->on('ceps')->constrained();
        $table->foreign('user_id')->references('id')->on('users')->constrained();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('alunos');
  }
};
