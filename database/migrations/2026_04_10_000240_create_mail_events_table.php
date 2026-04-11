<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mail_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mail_message_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 32);
            $table->string('summary');
            $table->json('payload')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['mail_account_id', 'occurred_at']);
            $table->index(['type', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_events');
    }
};
