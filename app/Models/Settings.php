<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Settings extends Model
{
    use Sushi;

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'allowed_users',
    ];

    protected $schema = [
        'id' => 'integer',
        'allowed_users' => 'string',
    ];

    public static function singletonKey(): int
    {
        return 1;
    }

    public static function refreshState(): void
    {
        static::$sushiConnection = null;
        static::clearBootedModels();
    }

    public function getRows(): array
    {
        return [[
            'id' => self::singletonKey(),
            'allowed_users' => json_encode(app(\App\Support\JsonSettingsService::class)->allowedUsers(), JSON_UNESCAPED_SLASHES),
        ]];
    }

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'allowed_users' => 'array',
        ];
    }
}
