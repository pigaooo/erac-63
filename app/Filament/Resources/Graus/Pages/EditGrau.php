<?php

namespace App\Filament\Resources\Graus\Pages;

use App\Filament\Resources\Graus\GrauResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrau extends EditRecord
{
    protected static string $resource = GrauResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}