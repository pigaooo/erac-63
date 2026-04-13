<?php

namespace App\Filament\Resources\Lojas;

use App\Filament\Resources\Lojas\Pages\CreateLoja;
use App\Filament\Resources\Lojas\Pages\EditLoja;
use App\Filament\Resources\Lojas\Pages\ListLojas;
use App\Filament\Resources\Lojas\Schemas\LojaForm;
use App\Filament\Resources\Lojas\Tables\LojasTable;
use App\Models\Loja;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LojaResource extends Resource
{
    protected static ?string $model = Loja::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Lojas';

    protected static ?string $modelLabel = 'Loja';

    protected static ?string $pluralModelLabel = 'Lojas';

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return LojaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LojasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLojas::route('/'),
            'create' => CreateLoja::route('/create'),
            'edit' => EditLoja::route('/{record}/edit'),
        ];
    }
}
