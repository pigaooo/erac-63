<?php

namespace App\Filament\Resources\Graus;

use App\Filament\Resources\Graus\Pages\CreateGrau;
use App\Filament\Resources\Graus\Pages\EditGrau;
use App\Filament\Resources\Graus\Pages\ListGraus;
use App\Filament\Resources\Graus\Schemas\GrauForm;
use App\Filament\Resources\Graus\Tables\GrausTable;
use App\Models\Grau;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class GrauResource extends Resource
{
    protected static ?string $model = Grau::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static ?string $navigationLabel = 'Graus';

    protected static ?string $modelLabel = 'Grau';

    protected static ?string $pluralModelLabel = 'Graus';

    protected static ?string $slug = 'graus';

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return GrauForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GrausTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGraus::route('/'),
            'create' => CreateGrau::route('/create'),
            'edit' => EditGrau::route('/{record}/edit'),
        ];
    }
}