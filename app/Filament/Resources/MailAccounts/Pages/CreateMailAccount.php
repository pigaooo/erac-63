<?php

namespace App\Filament\Resources\MailAccounts\Pages;

use App\Filament\Resources\MailAccounts\MailAccountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMailAccount extends CreateRecord
{
    protected static string $resource = MailAccountResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MailAccountResource::normalizeSharedSmtpData($data);
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
