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

    public function test_resume_inclui_countdown_no_lote_mais_proximo_de_expirar(): void
    {
        $calendar = app(InscricaoCalendar::class);

        $daySummary = $calendar->resumo(CarbonImmutable::parse('2026-04-29 10:00:00', 'America/Sao_Paulo'));
        $dayCountdown = $daySummary['lotes_visiveis'][0]['countdown'];

        $this->assertSame('1º lote', $daySummary['lotes_visiveis'][0]['label']);
        $this->assertSame('dias', $dayCountdown['unit']);
        $this->assertSame(1, $dayCountdown['value']);

        $hourSummary = $calendar->resumo(CarbonImmutable::parse('2026-04-30 20:00:00', 'America/Sao_Paulo'));
        $hourCountdown = $hourSummary['lotes_visiveis'][0]['countdown'];

        $this->assertSame('horas', $hourCountdown['unit']);
        $this->assertSame(3, $hourCountdown['value']);

        $minuteSummary = $calendar->resumo(CarbonImmutable::parse('2026-04-30 23:45:00', 'America/Sao_Paulo'));
        $minuteCountdown = $minuteSummary['lotes_visiveis'][0]['countdown'];

        $this->assertSame('minutos', $minuteCountdown['unit']);
        $this->assertSame(15, $minuteCountdown['value']);
        $this->assertNotNull($minuteCountdown['expires_at_iso']);
    }
}
