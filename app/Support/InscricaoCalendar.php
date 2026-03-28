<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class InscricaoCalendar
{
    public function inscricoesAbertas(?CarbonImmutable $date = null): bool
    {
        $today = $this->resolveDate($date);

        return $today->lessThanOrEqualTo($this->encerramentoOnline());
    }

    public function lotesVisiveis(?CarbonImmutable $date = null): array
    {
        $today = $this->resolveDate($date);

        return array_values(array_filter(
            $this->lotes(),
            fn (array $lote) => $today->lessThanOrEqualTo($lote['fim'])
        ));
    }

    public function loteAtual(?CarbonImmutable $date = null): ?array
    {
        $today = $this->resolveDate($date);

        foreach ($this->lotes() as $lote) {
            if ($today->betweenIncluded($lote['inicio'], $lote['fim'])) {
                return $lote;
            }
        }

        return null;
    }

    public function mensagemStatus(?CarbonImmutable $date = null): string
    {
        $today = $this->resolveDate($date);
        $encerramento = $this->encerramentoOnline();

        if ($today->lessThan($this->inicioInscricoes())) {
            return sprintf(
                'As inscrições on-line estão disponíveis para testes e seguem abertas até %s.',
                $encerramento->translatedFormat('d/m/Y')
            );
        }

        if ($today->greaterThan($encerramento)) {
            return sprintf(
                'As inscrições on-line foram encerradas em %s. Novas inscrições serão feitas somente no local do evento.',
                $encerramento->translatedFormat('d/m/Y')
            );
        }

        return sprintf('As inscrições on-line estão abertas até %s.', $encerramento->translatedFormat('d/m/Y'));
    }

    public function resumo(?CarbonImmutable $date = null): array
    {
        $dateTime = $this->resolveDateTime($date);
        $today = $dateTime->startOfDay();
        $lotesVisiveis = $this->lotesVisiveis($today);
        $loteMaisProximo = $lotesVisiveis[0]['id'] ?? null;

        $lotesVisiveis = array_map(function (array $lote) use ($dateTime, $loteMaisProximo): array {
            $countdown = $lote['id'] === $loteMaisProximo
                ? $this->countdownParaLote($lote, $dateTime)
                : null;

            return [
                ...$lote,
                'countdown' => $countdown,
            ];
        }, $lotesVisiveis);

        return [
            'inscricoes_abertas' => $this->inscricoesAbertas($today),
            'lotes_visiveis' => $lotesVisiveis,
            'lote_atual' => $this->loteAtual($today),
            'mensagem_status' => $this->mensagemStatus($today),
            'encerramento_online' => $this->encerramentoOnline(),
            'timezone' => $this->timezone(),
        ];
    }

    public function timezone(): string
    {
        return (string) config('inscricoes.timezone', 'America/Sao_Paulo');
    }

    private function lotes(): array
    {
        return array_map(function (array $lote): array {
            $inicio = CarbonImmutable::parse($lote['inicio'], $this->timezone())->startOfDay();
            $fim = CarbonImmutable::parse($lote['fim'], $this->timezone())->startOfDay();
            $expiraEm = $fim->endOfDay();

            return [
                ...$lote,
                'inicio' => $inicio,
                'fim' => $fim,
                'expira_em' => $expiraEm,
                'periodo' => sprintf('%s até %s', $inicio->translatedFormat('d/m'), $fim->translatedFormat('d/m')),
            ];
        }, config('inscricoes.lotes', []));
    }

    private function countdownParaLote(array $lote, CarbonImmutable $dateTime): ?array
    {
        $expiraEm = $lote['expira_em'] ?? null;

        if (! $expiraEm instanceof CarbonImmutable || $dateTime->greaterThan($expiraEm)) {
            return null;
        }

        $remainingSeconds = $dateTime->diffInSeconds($expiraEm);

        if ($remainingSeconds >= 86400) {
            $value = max(1, (int) ceil($dateTime->diffInDays($expiraEm)));

            return $this->formatCountdown($value, 'dias', $expiraEm);
        }

        if ($remainingSeconds >= 3600) {
            $value = max(1, (int) ceil($dateTime->diffInHours($expiraEm)));

            return $this->formatCountdown($value, 'horas', $expiraEm);
        }

        $value = max(1, (int) ceil($remainingSeconds / 60));

        return $this->formatCountdown($value, 'minutos', $expiraEm);
    }

    private function formatCountdown(int $value, string $unit, CarbonImmutable $expiraEm): array
    {
        return [
            'value' => $value,
            'unit' => $unit,
            'expires_at_iso' => $expiraEm->toIso8601String(),
        ];
    }

    private function inicioInscricoes(): CarbonImmutable
    {
        $primeiroLote = $this->lotes()[0] ?? null;

        return $primeiroLote['inicio'] ?? $this->encerramentoOnline();
    }

    private function encerramentoOnline(): CarbonImmutable
    {
        return CarbonImmutable::parse(config('inscricoes.encerramento_online'), $this->timezone())->startOfDay();
    }

    private function resolveDate(?CarbonImmutable $date = null): CarbonImmutable
    {
        return $this->resolveDateTime($date)->startOfDay();
    }

    private function resolveDateTime(?CarbonImmutable $date = null): CarbonImmutable
    {
        return ($date ?? CarbonImmutable::now($this->timezone()))
            ->setTimezone($this->timezone());
    }
}
