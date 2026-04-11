<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_folders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mail_account_id')->constrained()->cascadeOnDelete();
            $table->string('remote_name');
            $table->string('display_name');
            $table->string('delimiter', 4)->nullable();
            $table->json('attributes')->nullable();
            $table->string('special_use')->nullable();
            $table->string('uid_validity')->nullable();
            $table->string('remote_hash')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_selectable')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->unique(['mail_account_id', 'remote_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_folders');
    }
};
