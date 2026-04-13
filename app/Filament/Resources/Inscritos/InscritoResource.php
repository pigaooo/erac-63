<?php

namespace App\Filament\Resources\Inscritos;

use App\Filament\Resources\Inscritos\Pages\CreateInscrito;
use App\Filament\Resources\Inscritos\Pages\EditInscrito;
use App\Filament\Resources\Inscritos\Pages\ListInscritos;
use App\Filament\Resources\Inscritos\Schemas\InscritoForm;
use App\Filament\Resources\Inscritos\Tables\InscritosTable;
use App\Models\Inscrito;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class InscritoResource extends Resource
{
    protected static ?string $model = Inscrito::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Inscritos';

    protected static ?string $modelLabel = 'Inscrito';

    protected static ?string $pluralModelLabel = 'Inscritos';

    protected static ?string $slug = 'inscritos';

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return InscritoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InscritosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInscritos::route('/'),
            'create' => CreateInscrito::route('/create'),
            'edit' => EditInscrito::route('/{record}/edit'),
        ];
    }
}
