<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email_address');
            $table->string('from_name')->nullable();
            $table->string('imap_host');
            $table->unsignedSmallInteger('imap_port')->default(993);
            $table->string('imap_encryption', 16)->default('ssl');
            $table->string('imap_username');
            $table->text('imap_password');
            $table->boolean('imap_validate_cert')->default(true);
            $table->string('smtp_host');
            $table->unsignedSmallInteger('smtp_port')->default(465);
            $table->string('smtp_encryption', 16)->default('ssl');
            $table->string('smtp_username');
            $table->text('smtp_password');
            $table->string('inbox_folder_name')->default('INBOX');
            $table->string('sent_folder_name')->nullable();
            $table->string('drafts_folder_name')->nullable();
            $table->string('spam_folder_name')->nullable();
            $table->string('trash_folder_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sync_interval_minutes')->default(5);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('last_error_at')->nullable();
            $table->text('last_error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_accounts');
    }
};
