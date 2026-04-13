<?php

namespace App\Filament\Resources\Inscritos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InscritoForm
{
    public static function configure(Schema $schema): Schema
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
                                'AM' => 'Aâˆ´Mâˆ´',
                                'CM' => 'Câˆ´Mâˆ´',
                                'MM' => 'Mâˆ´Mâˆ´',
                                'MI' => 'Mâˆ´Iâˆ´',
                                'OT' => 'Outros',
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
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
            ]);
    }
}
