<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingsResource;
use App\Models\Settings;
use App\Support\JsonSettingsService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSettings extends EditRecord
{
    protected static string $resource = SettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['allowed_users'] = array_values(array_map(
            fn (array $allowedUser): string => (string) ($allowedUser['email'] ?? ''),
            $data['allowed_users'] ?? [],
        ));

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['allowed_users'] = app(JsonSettingsService::class)->normalizeAllowedUsers($data['allowed_users'] ?? []);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        app(JsonSettingsService::class)->saveAllowedUsers($data['allowed_users'] ?? []);

        $freshRecord = Settings::query()->findOrFail(Settings::singletonKey());

        $record->forceFill($freshRecord->attributesToArray());

        return $record;
    }

    protected function afterSave(): void
    {
        $this->record = Settings::query()->findOrFail(Settings::singletonKey());
        $this->form->fill($this->mutateFormDataBeforeFill($this->record->attributesToArray()));
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Settings salvos com sucesso.';
    }
}
