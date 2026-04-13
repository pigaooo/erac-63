<?php

namespace App\Filament\Resources\Lojas\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LojaForm
{
    public static function configure(Schema $schema): Schema
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
                            ->default(fn () => Auth::id()),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
            ]);
    }
}
