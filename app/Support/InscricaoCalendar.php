<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class InscricaoCalendar
{
    public function inscricoesAbertas(?CarbonImmutable $date = null): bool
    {
        $today = $this->resolveDate($date);

        return $today->betweenIncluded($this->inicioInscricoes(), $this->encerramentoOnline());
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
        $inicio = $this->inicioInscricoes();
        $encerramento = $this->encerramentoOnline();

        if ($today->lessThan($inicio)) {
            return sprintf('As inscrições on-line começam em %s.', $inicio->translatedFormat('d/m/Y'));
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
        $today = $this->resolveDate($date);

        return [
            'inscricoes_abertas' => $this->inscricoesAbertas($today),
            'lotes_visiveis' => $this->lotesVisiveis($today),
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

            return [
                ...$lote,
                'inicio' => $inicio,
                'fim' => $fim,
                'periodo' => sprintf('%s até %s', $inicio->translatedFormat('d/m'), $fim->translatedFormat('d/m')),
            ];
        }, config('inscricoes.lotes', []));
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
        return ($date ?? CarbonImmutable::now($this->timezone()))
            ->setTimezone($this->timezone())
            ->startOfDay();
    }
}
