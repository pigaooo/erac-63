<?php

namespace App\Filament\Resources\MailAccountResource\Pages;

use App\Filament\Resources\MailAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMailAccount extends EditRecord
{
    protected static string $resource = MailAccountResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MailAccountResource::normalizeSharedSmtpData($data);

        if (blank($data['imap_password'] ?? null)) {
            unset($data['imap_password']);
        }

        if (blank($data['smtp_password'] ?? null)) {
            unset($data['smtp_password']);
        }

        return $data;
    }

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
