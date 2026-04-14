<?php

namespace App\Filament\Resources\Graus\Pages;

use App\Filament\Resources\Graus\GrauResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGraus extends ListRecords
{
    protected static string $resource = GrauResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}