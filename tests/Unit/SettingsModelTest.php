<?php

namespace Tests\Unit;

use App\Models\Settings;
use Tests\Concerns\InteractsWithSettingsFile;
use Tests\TestCase;

class SettingsModelTest extends TestCase
{
    use InteractsWithSettingsFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useTemporarySettingsFile('settings-model-test.json');
    }

    protected function tearDown(): void
    {
        $this->tearDownSettingsFile();

        parent::tearDown();
    }

    public function test_expoe_o_singleton_com_usuarios_permitidos_do_json(): void
    {
        $this->writeSettingsFile([
            'filament' => [
                'allowed_users' => [
                    [
                        'name' => 'Admin ERAC',
                        'email' => 'admin@erac.test',
                    ],
                ],
            ],
        ]);

        $settings = Settings::query()->findOrFail(Settings::singletonKey());

        $this->assertSame(Settings::singletonKey(), $settings->id);
        $this->assertSame([
            [
                'name' => 'Admin ERAC',
                'email' => 'admin@erac.test',
            ],
        ], $settings->allowed_users);
    }
}
