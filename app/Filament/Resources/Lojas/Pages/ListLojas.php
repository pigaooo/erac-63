<?php

namespace App\Filament\Resources\Lojas\Pages;

use App\Filament\Resources\Lojas\LojaResource;
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
