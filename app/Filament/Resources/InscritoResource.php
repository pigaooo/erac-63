<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscritoResource\Pages\CreateInscrito;
use App\Filament\Resources\InscritoResource\Pages\EditInscrito;
use App\Filament\Resources\InscritoResource\Pages\ListInscritos;
use App\Models\Inscrito;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
        return $schema
            ->components([
                Section::make('Dados do inscrito')
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
                        TextInput::make('cpf')
                            ->label('CPF')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        TextInput::make('cim')
                            ->label('CIM')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('grau')
                            ->label('Grau')
                            ->options([
                                'AM' => 'A∴M∴',
                                'CM' => 'C∴M∴',
                                'MM' => 'M∴M∴',
                                'MI' => 'M∴I∴',
                            ])
                            ->required(),
                        Select::make('loja_id')
                            ->label('Loja')
                            ->relationship('loja', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Toggle::make('is_paied')
                            ->label('Pagamento confirmado'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Inscrito')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('loja.name')
                    ->label('Loja')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grau')
                    ->label('Grau')
                    ->badge()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('telefone')
                    ->label('Telefone')
                    ->toggleable(),
                TextColumn::make('cpf')
                    ->label('CPF')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cim')
                    ->label('CIM')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_paied')
                    ->label('Pago')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Cadastro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_paied')
                    ->label('Pagamento'),
                SelectFilter::make('grau')
                    ->label('Grau')
                    ->options([
                        'AM' => 'A∴M∴',
                        'CM' => 'C∴M∴',
                        'MM' => 'M∴M∴',
                        'MI' => 'M∴I∴',
                    ]),
                SelectFilter::make('loja_id')
                    ->label('Loja')
                    ->relationship('loja', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => ListInscritos::route('/'),
            'create' => CreateInscrito::route('/create'),
            'edit' => EditInscrito::route('/{record}/edit'),
        ];
    }
}
