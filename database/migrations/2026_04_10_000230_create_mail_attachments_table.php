<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mail_message_id')->constrained()->cascadeOnDelete();
            $table->string('part_number');
            $table->string('filename')->nullable();
            $table->string('content_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('content_id')->nullable();
            $table->string('path')->nullable();
            $table->boolean('is_inline')->default(false);
            $table->boolean('is_downloaded')->default(false);
            $table->timestamps();

            $table->unique(['mail_message_id', 'part_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_attachments');
    }
};
