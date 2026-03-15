<?php

namespace App\Filament\Resources\PatrocinadorResource\Pages;

use App\Filament\Resources\PatrocinadorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatrocinadores extends ListRecords
{
    protected static string $resource = PatrocinadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
