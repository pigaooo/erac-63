<?php

namespace App\Filament\Resources\PatrocinadorResource\Pages;

use App\Filament\Resources\PatrocinadorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatrocinador extends EditRecord
{
    protected static string $resource = PatrocinadorResource::class;

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
