<?php

namespace App\Filament\Resources\PatrocinadorResource\Pages;

use App\Filament\Resources\PatrocinadorResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatrocinador extends CreateRecord
{
    protected static string $resource = PatrocinadorResource::class;

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
