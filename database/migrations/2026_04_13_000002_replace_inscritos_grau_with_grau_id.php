<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->foreignUlid('grau_id')
                ->nullable()
                ->after('cim')
                ->constrained('graus');
        });

        $grausPorCodigo = DB::table('graus')->pluck('id', 'codigo');

        DB::table('inscritos')
            ->select(['id', 'grau'])
            ->orderBy('id')
            ->get()
            ->each(function (object $inscrito) use ($grausPorCodigo): void {
                $grauId = $grausPorCodigo[$inscrito->grau] ?? $grausPorCodigo['OT'] ?? null;

                DB::table('inscritos')
                    ->where('id', $inscrito->id)
                    ->update(['grau_id' => $grauId]);
            });

        Schema::table('inscritos', function (Blueprint $table) {
            $table->dropColumn('grau');
        });
    }

    public function down(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->string('grau')->default('OT')->after('cim');
        });

        $grausPorId = DB::table('graus')->pluck('codigo', 'id');

        DB::table('inscritos')
            ->select(['id', 'grau_id'])
            ->orderBy('id')
            ->get()
            ->each(function (object $inscrito) use ($grausPorId): void {
                $codigo = $grausPorId[$inscrito->grau_id] ?? 'OT';

                DB::table('inscritos')
                    ->where('id', $inscrito->id)
                    ->update(['grau' => $codigo]);
            });

        Schema::table('inscritos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('grau_id');
        });
    }
};