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
    if (!Schema::hasTable('ceps')) {
      Schema::create('ceps', function (Blueprint $table) {
        $table->id();
        $table->string("cep", 16);
        $table->string("endereco", 64);
        $table->string("bairro", 64);
        $table->string("cidade", 64);
        $table->string("uf", 2);
        $table->boolean("manual");
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable();

        $table->index('cep');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ceps');
  }
};
