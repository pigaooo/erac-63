<?php

namespace App\Support;

use App\Models\Settings;
use Illuminate\Filesystem\Filesystem;

class JsonSettingsService
{
    public function __construct(
        private readonly Filesystem $files,
    ) {}

    public function path(): string
    {
        $configuredPath = trim((string) config('settings.path', 'settings.json'));
        $fallbackPath = base_path('settings.json');

        if ($configuredPath === '') {
            return $fallbackPath;
        }

        $resolvedPath = $this->isAbsolutePath($configuredPath)
            ? $configuredPath
            : base_path($configuredPath);

        if (
            $resolvedPath !== $fallbackPath
            && ! $this->files->exists($resolvedPath)
            && $this->files->exists($fallbackPath)
        ) {
            return $fallbackPath;
        }

        return $resolvedPath;
    }

    public function all(): array
    {
        $data = $this->defaults();
        $path = $this->path();

        if (! $this->files->exists($path)) {
            return $data;
        }

        try {
            $decoded = json_decode($this->files->get($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return $data;
        }

        if (! is_array($decoded)) {
            return $data;
        }

        $data = array_replace_recursive($data, $decoded);
        $data['filament']['allowed_users'] = $this->normalizeAllowedUsers($data['filament']['allowed_users'] ?? []);

        return $data;
    }

    public function defaults(): array
    {
        return [
            'filament' => [
                'allowed_users' => [],
            ],
        ];
    }

    public function allowedUsers(): array
    {
        return $this->all()['filament']['allowed_users'];
    }

    public function allowedEmails(): array
    {
        return array_values(array_map(
            fn (array $user): string => $user['email'],
            $this->allowedUsers(),
        ));
    }

    public function isAllowedEmail(?string $email): bool
    {
        $email = $this->normalizeEmail($email);

        if ($email === null) {
            return false;
        }

        return in_array($email, $this->allowedEmails(), true);
    }

    public function saveAllowedUsers(array $allowedUsers): array
    {
        $data = $this->all();
        $data['filament']['allowed_users'] = $this->normalizeAllowedUsers($allowedUsers);

        $this->write($data);
        Settings::refreshState();

        return $data['filament']['allowed_users'];
    }

    public function syncAllowedUsers(iterable $users): array
    {
        $allowedUsers = [];

        foreach ($users as $user) {
            if (is_array($user)) {
                $allowedUsers[] = [
                    'name' => $user['name'] ?? null,
                    'email' => $user['email'] ?? null,
                ];

                continue;
            }

            $allowedUsers[] = [
                'name' => $user->name ?? null,
                'email' => $user->email ?? null,
            ];
        }

        return $this->saveAllowedUsers($allowedUsers);
    }

    public function normalizeAllowedUsers(array $allowedUsers): array
    {
        $normalized = [];

        foreach ($allowedUsers as $allowedUser) {
            if (is_string($allowedUser)) {
                $allowedUser = ['email' => $allowedUser];
            }

            if (! is_array($allowedUser)) {
                continue;
            }

            $email = $this->normalizeEmail($allowedUser['email'] ?? null);

            if ($email === null || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            if (array_key_exists($email, $normalized)) {
                continue;
            }

            $name = trim((string) ($allowedUser['name'] ?? ''));

            $normalized[$email] = array_filter([
                'name' => $name !== '' ? $name : null,
                'email' => $email,
            ], fn (mixed $value): bool => $value !== null);
        }

        return array_values($normalized);
    }

    private function normalizeEmail(mixed $email): ?string
    {
        $email = trim(strtolower((string) $email));

        return $email !== '' ? $email : null;
    }

    private function write(array $data): void
    {
        $path = $this->path();
        $directory = dirname($path);

        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            throw new \RuntimeException('Unable to encode settings.json.');
        }

        $this->files->replace($path, $json . PHP_EOL);
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR)
            || preg_match('/^[A-Za-z]:\\\\/', $path) === 1;
    }
}
