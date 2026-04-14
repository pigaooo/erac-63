<?php

namespace App\Filament\Resources\Graus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GrauForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados do grau')
                    ->schema([
                        TextInput::make('codigo')
                            ->label('Codigo')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper(trim((string) $state))),
                        TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('ordem')
                            ->label('Ordem')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('ativo')
                            ->label('Ativo')
                            ->default(true),
                        Toggle::make('tipo_especial')
                            ->label('Tipo especial')
                            ->default(false),
                        Toggle::make('disponivel_formulario_individual')
                            ->label('Disponivel no formulario individual')
                            ->default(true),
                        Toggle::make('disponivel_formulario_multiplos')
                            ->label('Disponivel no formulario multiplo')
                            ->default(true),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
            ]);
    }
}