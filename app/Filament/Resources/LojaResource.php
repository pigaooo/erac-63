<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LojaResource\Pages\CreateLoja;
use App\Filament\Resources\LojaResource\Pages\EditLoja;
use App\Filament\Resources\LojaResource\Pages\ListLojas;
use App\Models\Loja;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
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
        return $schema
            ->components([
                Section::make('Dados da loja')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome da loja')
                            ->required()
                            ->maxLength(150),
                        TextInput::make('numero_loja')
                            ->label('Numero da loja')
                            ->required()
                            ->numeric(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        Toggle::make('is_ativo')
                            ->label('Loja ativa')
                            ->default(true),
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
                    ->label('Loja')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('numero_loja')
                    ->label('Numero')
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                IconColumn::make('is_ativo')
                    ->label('Ativa')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_ativo')
                    ->label('Status'),
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
            'index' => ListLojas::route('/'),
            'create' => CreateLoja::route('/create'),
            'edit' => EditLoja::route('/{record}/edit'),
        ];
    }
}
