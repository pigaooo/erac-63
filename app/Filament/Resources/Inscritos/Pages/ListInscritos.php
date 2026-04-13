<?php

namespace App\Filament\Resources\Inscritos\Pages;

use App\Filament\Resources\Inscritos\InscritoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInscritos extends ListRecords
{
    protected static string $resource = InscritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
