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
        Schema::create('lojas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name', 150);
            $table->integer('numero_loja');
            $table->string('email', 100)->unique();
            $table->boolean('is_ativo')->default(true);
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lojas');
    }
};
