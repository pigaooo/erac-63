<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use App\Models\Loja;
use App\Models\Patrocinador;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EracOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Visao geral do evento';

    protected ?string $description = 'Resumo rapido dos principais cadastros do ERAC.';

    protected function getStats(): array
    {
        $totalInscritos = Inscrito::query()->count();
        $totalPagos = Inscrito::query()->where('is_paied', true)->count();

        return [
            Stat::make('Lojas cadastradas', Loja::query()->count())
                ->description('Lojas disponiveis para inscricao e administracao')
                ->color('primary'),
            Stat::make('Patrocinadores', Patrocinador::query()->count())
                ->description('Marcas e apoiadores do evento')
                ->color('success'),
            Stat::make('Inscritos', $totalInscritos)
                ->description('Participantes cadastrados no ERAC')
                ->color('warning'),
            Stat::make('Pagamentos confirmados', $totalPagos)
                ->description("{$totalPagos} de {$totalInscritos} inscritos marcados como pagos")
                ->color('info'),
        ];
    }
}
