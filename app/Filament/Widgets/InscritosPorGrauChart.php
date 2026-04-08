<?php

namespace App\Filament\Widgets;

use App\Models\Inscrito;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InscritosPorGrauChart extends StatsOverviewWidget
{
    protected ?string $heading = 'Irmaos por grau';

    protected ?string $description = 'Contagem de inscritos agrupada por grau maconico.';

    protected int | string | array $columnSpan = 'full';

    private const GRAUS = [
        ['code' => 'AM', 'label' => 'Aprendizes', 'description' => 'Irmaos no grau A.M.', 'color' => 'warning'],
        ['code' => 'CM', 'label' => 'Companheiros', 'description' => 'Irmaos no grau C.M.', 'color' => 'info'],
        ['code' => 'MM', 'label' => 'Mestres', 'description' => 'Irmaos no grau M.M.', 'color' => 'success'],
        ['code' => 'MI', 'label' => 'Mestres Instalados', 'description' => 'Irmaos no grau M.I.', 'color' => 'primary'],
        ['code' => 'OT', 'label' => 'Outros', 'description' => 'Inscritos classificados como outros', 'color' => 'gray'],
        ['code' => 'VI', 'label' => 'Visitantes', 'description' => 'Inscritos classificados como visitantes', 'color' => 'danger'],
        ['code' => 'CU', 'label' => 'Cunhadas', 'description' => 'Inscritas classificadas como cunhadas', 'color' => 'success'],
        ['code' => 'SO', 'label' => 'Sobrinhos', 'description' => 'Inscritos classificados como sobrinhos', 'color' => 'info'],
    ];

    protected function getStats(): array
    {
        $counts = Inscrito::query()
            ->selectRaw('grau, COUNT(*) as total')
            ->groupBy('grau')
            ->pluck('total', 'grau');

        return array_map(
            fn (array $grau): Stat => Stat::make($grau['label'], (int) ($counts[$grau['code']] ?? 0))
                ->description($grau['description'])
                ->color($grau['color']),
            self::GRAUS
        );
    }
}
