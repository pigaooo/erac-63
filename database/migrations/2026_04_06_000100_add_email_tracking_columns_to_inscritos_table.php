<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->timestamp('registration_confirmation_sent_at')->nullable()->after('is_paied');
            $table->timestamp('payment_confirmation_sent_at')->nullable()->after('registration_confirmation_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->dropColumn([
                'registration_confirmation_sent_at',
                'payment_confirmation_sent_at',
            ]);
        });
    }
};
