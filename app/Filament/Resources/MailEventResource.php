<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailEventResource\Pages\ListMailEvents;
use App\Models\MailEvent;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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
        return $table
            ->defaultSort('occurred_at', 'desc')
            ->columns([
                TextColumn::make('occurred_at')->label('Quando')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('account.name')->label('Conta')->searchable(),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('summary')->label('Resumo')->wrap(),
                IconColumn::make('is_read')->label('Lido')->boolean(),
            ]);
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
