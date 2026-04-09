<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages\EditSettings;
use App\Models\Settings;
use Filament\Forms\Components\TagsInput;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use function Filament\Support\original_request;

class SettingsResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $modelLabel = 'Setting';

    protected static ?string $pluralModelLabel = 'Settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistema';

    protected static ?int $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Acesso ao Filament')
                    ->description('Apenas usuarios ativos com e-mail listado abaixo podem acessar o painel.')
                    ->schema([
                        TagsInput::make('allowed_users')
                            ->label('Usuarios permitidos')
                            ->placeholder('Digite um e-mail e pressione Enter')
                            ->helperText('Adicione um e-mail por vez. Cada item pode ser removido no X da badge.')
                            ->splitKeys(['Enter', 'Tab'])
                            ->nestedRecursiveRules([
                                'email',
                                'max:150',
                            ])
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getNavigationItems(): array
    {
        if (! static::canAccess()) {
            return [];
        }

        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn (): bool => original_request()->routeIs(static::getRouteBaseName() . '.*'))
                ->sort(static::getNavigationSort())
                ->url(static::getNavigationUrl()),
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('edit', ['record' => Settings::singletonKey()]);
    }

    public static function getIndexUrl(
        array $parameters = [],
        bool $isAbsolute = true,
        ?string $panel = null,
        ?\Illuminate\Database\Eloquent\Model $tenant = null,
        bool $shouldGuessMissingParameters = false,
    ): string {
        return static::getUrl('edit', [
            ...$parameters,
            'record' => Settings::singletonKey(),
        ], $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters);
    }

    public static function getPages(): array
    {
        return [
            'edit' => EditSettings::route('/{record}/edit'),
        ];
    }
}
