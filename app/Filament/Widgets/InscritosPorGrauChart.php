<?php

namespace App\Filament\Widgets;

use App\Models\Grau;
use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InscritosPorGrauChart extends StatsOverviewWidget
{
    protected ?string $heading = 'Irmaos por grau';

    protected ?string $description = 'Contagem de inscritos agrupada por grau maconico.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return Grau::query()
            ->withCount('inscritos')
            ->ordenados()
            ->get()
            ->map(fn (Grau $grau): Stat => Stat::make($grau->nome, $grau->inscritos_count)
                ->description('Inscritos classificados em ' . $grau->nome)
                ->color($grau->tipo_especial ? 'gray' : 'primary'))
            ->all();
    }
}
