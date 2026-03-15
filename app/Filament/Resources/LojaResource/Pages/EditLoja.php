<?php

namespace App\Filament\Resources\LojaResource\Pages;

use App\Filament\Resources\LojaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoja extends EditRecord
{
    protected static string $resource = LojaResource::class;

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
