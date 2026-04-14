<?php

namespace App\Filament\Resources\Graus\Pages;

use App\Filament\Resources\Graus\GrauResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGrau extends CreateRecord
{
    protected static string $resource = GrauResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}