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
        Schema::create('inscritos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('telefone', 50)->nullable();
            $table->string('cpf', 20)->unique();
            $table->string('cim')->unique();
            $table->string('grau');
            $table->foreignUlid('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->boolean('is_paied')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
