<?php

namespace App\Filament\Resources\Inscritos\Pages;

use App\Filament\Resources\Inscritos\InscritoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInscrito extends CreateRecord
{
    protected static string $resource = InscritoResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
