<?php

namespace Tests\Unit;

use App\Support\InscricaoCalendar;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class InscricaoCalendarTest extends TestCase
{
    public function test_exibe_apenas_lotes_ainda_nao_expirados(): void
    {
        $calendar = app(InscricaoCalendar::class);
        $date = CarbonImmutable::parse('2026-05-10', 'America/Sao_Paulo');

        $lotes = $calendar->lotesVisiveis($date);

        $this->assertCount(2, $lotes);
        $this->assertSame('2º lote', $calendar->loteAtual($date)['label']);
        $this->assertSame(['2º lote', '3º lote'], array_column($lotes, 'label'));
    }

    public function test_fecha_inscricoes_apos_encerramento_online(): void
    {
        $calendar = app(InscricaoCalendar::class);
        $date = CarbonImmutable::parse('2026-06-21', 'America/Sao_Paulo');

        $this->assertFalse($calendar->inscricoesAbertas($date));
        $this->assertSame([], $calendar->lotesVisiveis($date));
        $this->assertStringContainsString('somente no local do evento', $calendar->mensagemStatus($date));
    }
}
