<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InscritosPorGrauChart extends StatsOverviewWidget
{
    protected ?string $heading = 'Irmãos por grau';

    protected ?string $description = 'Contagem de inscritos agrupada por grau maçônico.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $counts = Inscrito::query()
            ->selectRaw('grau, COUNT(*) as total')
            ->groupBy('grau')
            ->pluck('total', 'grau');

        return [
            Stat::make('Aprendizes', (int) ($counts['AM'] ?? 0))
                ->description('Irmãos no grau A∴M∴')
                ->color('warning'),
            Stat::make('Companheiros', (int) ($counts['CM'] ?? 0))
                ->description('Irmãos no grau C∴M∴')
                ->color('info'),
            Stat::make('Mestres', (int) ($counts['MM'] ?? 0))
                ->description('Irmãos no grau M∴M∴')
                ->color('success'),
            Stat::make('Mestres Instalados', (int) ($counts['MI'] ?? 0))
                ->description('Irmãos no grau M∴I∴')
                ->color('primary'),
            Stat::make('Outros', (int) ($counts['OT'] ?? 0))
                ->description('Inscritos classificados como outros')
                ->color('gray'),
        ];
    }
}