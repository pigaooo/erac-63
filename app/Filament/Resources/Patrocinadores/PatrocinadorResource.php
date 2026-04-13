<?php

namespace App\Filament\Resources\Patrocinadores;

use App\Filament\Resources\Patrocinadores\Pages\CreatePatrocinador;
use App\Filament\Resources\Patrocinadores\Pages\EditPatrocinador;
use App\Filament\Resources\Patrocinadores\Pages\ListPatrocinadores;
use App\Filament\Resources\Patrocinadores\Schemas\PatrocinadorForm;
use App\Filament\Resources\Patrocinadores\Tables\PatrocinadoresTable;
use App\Models\Patrocinador;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PatrocinadorResource extends Resource
{
    protected static ?string $model = Patrocinador::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Patrocinadores';

    protected static ?string $modelLabel = 'Patrocinador';

    protected static ?string $pluralModelLabel = 'Patrocinadores';

    protected static ?string $slug = 'patrocinadores';

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PatrocinadorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatrocinadoresTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatrocinadores::route('/'),
            'create' => CreatePatrocinador::route('/create'),
            'edit' => EditPatrocinador::route('/{record}/edit'),
        ];
    }
}
