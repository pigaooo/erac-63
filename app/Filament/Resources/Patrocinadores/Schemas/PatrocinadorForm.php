<?php

namespace App\Filament\Resources\Patrocinadores\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatrocinadorForm
{
    public static function configure(Schema $schema): Schema
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
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Logo do patrocinador')
                            ->collection('logo')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                    ])
                    ->columns(2),
            ]);
    }
}
