<?php

namespace App\Filament\Resources\InscritoResource\Pages;

use App\Filament\Resources\InscritoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInscrito extends EditRecord
{
    protected static string $resource = InscritoResource::class;

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
