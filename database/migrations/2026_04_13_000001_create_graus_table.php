<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graus', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('codigo', 10)->unique();
            $table->string('nome', 100);
            $table->unsignedInteger('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->boolean('tipo_especial')->default(false);
            $table->boolean('disponivel_formulario_individual')->default(true);
            $table->boolean('disponivel_formulario_multiplos')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graus');
    }
};