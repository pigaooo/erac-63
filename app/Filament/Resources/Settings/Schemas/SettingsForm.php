<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Acesso ao Filament')
                    ->description('Apenas usuarios ativos com e-mail listado abaixo podem acessar o painel.')
                    ->schema([
                        TagsInput::make('allowed_users')
                            ->label('Usuarios permitidos')
                            ->placeholder('Digite um e-mail e pressione Enter')
                            ->helperText('Adicione um e-mail por vez. Cada item pode ser removido no X da badge.')
                            ->splitKeys(['Enter', 'Tab'])
                            ->nestedRecursiveRules([
                                'email',
                                'max:150',
                            ])
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
