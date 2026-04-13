<?php

namespace App\Filament\Resources\MailEvents;

use App\Filament\Resources\MailEvents\Pages\ListMailEvents;
use App\Filament\Resources\MailEvents\Tables\MailEventsTable;
use App\Models\MailEvent;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class MailEventResource extends Resource
{
    protected static ?string $model = MailEvent::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Eventos de email';

    protected static ?string $modelLabel = 'Evento de email';

    protected static ?string $pluralModelLabel = 'Eventos de email';

    protected static string | \UnitEnum | null $navigationGroup = 'Email';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return MailEventsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMailEvents::route('/'),
        ];
    }
}
