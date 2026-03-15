<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatrocinadorResource\Pages\CreatePatrocinador;
use App\Filament\Resources\PatrocinadorResource\Pages\EditPatrocinador;
use App\Filament\Resources\PatrocinadorResource\Pages\ListPatrocinadores;
use App\Models\Patrocinador;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
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
        return $schema
            ->components([
                Section::make('Dados do patrocinador')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(150),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(150)
                            ->unique(ignoreRecord: true),
                        TextInput::make('telefone')
                            ->label('Telefone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('endereco')
                            ->label('Site / endereco')
                            ->maxLength(255),
                        Select::make('tipo_patrocinio')
                            ->label('Tipo de patrocinio')
                            ->options([
                                'Diamante' => 'Diamante',
                                'Ouro' => 'Ouro',
                                'Prata' => 'Prata',
                                'Bronze' => 'Bronze',
                                'Apoio' => 'Apoio',
                            ])
                            ->required(),
                        Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Patrocinador')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipo_patrocinio')
                    ->label('Cota')
                    ->badge()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('telefone')
                    ->label('Telefone')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
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
