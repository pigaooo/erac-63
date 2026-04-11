<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mail_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mail_folder_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('uid');
            $table->string('remote_message_id')->nullable();
            $table->string('subject')->nullable();
            $table->json('from_addresses')->nullable();
            $table->json('to_addresses')->nullable();
            $table->json('cc_addresses')->nullable();
            $table->json('bcc_addresses')->nullable();
            $table->json('reply_to_addresses')->nullable();
            $table->json('headers')->nullable();
            $table->text('snippet')->nullable();
            $table->longText('text_body')->nullable();
            $table->longText('html_body')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->boolean('has_attachments')->default(false);
            $table->boolean('is_seen')->default(false);
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->string('direction', 16)->default('inbound');
            $table->string('sync_status', 32)->default('synced');
            $table->timestamp('last_remote_update_at')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->unique(['mail_account_id', 'mail_folder_id', 'uid']);
            $table->index(['mail_folder_id', 'received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_messages');
    }
};
