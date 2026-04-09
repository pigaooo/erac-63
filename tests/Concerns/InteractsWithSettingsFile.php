<?php

namespace Tests\Concerns;

use App\Models\Settings;
use Illuminate\Support\Facades\File;

trait InteractsWithSettingsFile
{
    protected string $settingsTestPath;

    protected function useTemporarySettingsFile(string $fileName = 'settings-test.json'): void
    {
        $directory = storage_path('framework/testing');
        File::ensureDirectoryExists($directory);

        $this->settingsTestPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        config()->set('settings.path', $this->settingsTestPath);

        File::delete($this->settingsTestPath);
        Settings::refreshState();
    }

    protected function writeSettingsFile(array $data): void
    {
        File::put(
            $this->settingsTestPath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL,
        );

        Settings::refreshState();
    }

    protected function tearDownSettingsFile(): void
    {
        if (isset($this->settingsTestPath)) {
            File::delete($this->settingsTestPath);
        }

        Settings::refreshState();
    }
}
