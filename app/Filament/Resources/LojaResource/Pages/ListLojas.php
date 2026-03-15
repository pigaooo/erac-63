<?php

namespace App\Filament\Resources\LojaResource\Pages;

use App\Filament\Resources\LojaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLojas extends ListRecords
{
    protected static string $resource = LojaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
