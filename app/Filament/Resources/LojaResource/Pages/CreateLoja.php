<?php

namespace App\Filament\Resources\LojaResource\Pages;

use App\Filament\Resources\LojaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoja extends CreateRecord
{
    protected static string $resource = LojaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
