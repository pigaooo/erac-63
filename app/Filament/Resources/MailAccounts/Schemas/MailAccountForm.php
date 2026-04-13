<?php

namespace App\Filament\Resources\MailAccounts\Schemas;

use App\Models\MailAccount;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MailAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conta')
                    ->schema([
                        TextInput::make('name')->label('Nome interno')->required()->maxLength(120),
                        TextInput::make('email_address')->label('Email da conta')->email()->required()->maxLength(150),
                        TextInput::make('from_name')->label('Nome do remetente')->maxLength(150),
                        Toggle::make('is_active')->label('Conta ativa')->default(true),
                        TextInput::make('sync_interval_minutes')->label('Intervalo de sincronizacao (minutos)')->numeric()->default(5)->minValue(1)->required(),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
                Section::make('IMAP')
                    ->schema([
                        Toggle::make('use_imap_credentials_for_smtp')
                            ->label('Usar mesmo servidor e credenciais para envio')
                            ->helperText('Quando ativo, host, criptografia, usuario e senha do SMTP serao copiados do IMAP ao salvar. Voce so precisa informar a porta SMTP.')
                            ->live()
                            ->default(true)
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Toggle $component, ?MailAccount $record): void {
                                if ($record === null) {
                                    $component->state(true);

                                    return;
                                }

                                $sameServer = $record->imap_host === $record->smtp_host
                                    && $record->imap_encryption === $record->smtp_encryption
                                    && $record->imap_username === $record->smtp_username;

                                $component->state($sameServer);
                            }),
                        TextInput::make('imap_host')->label('Host IMAP')->required(),
                        TextInput::make('imap_port')->label('Porta IMAP')->numeric()->default(993)->required(),
                        TextInput::make('imap_encryption')->label('Criptografia IMAP')->default('ssl')->required(),
                        TextInput::make('imap_username')->label('Usuario IMAP')->required(),
                        TextInput::make('imap_password')
                            ->label('Senha IMAP')
                            ->password()
                            ->revealable()
                            ->helperText('Na edicao, deixe em branco para manter a senha atual.')
                            ->autocomplete('new-password')
                            ->afterStateHydrated(fn ($component) => $component->state(''))
                            ->dehydrated(fn ($state): bool => filled($state)),
                        Toggle::make('imap_validate_cert')->label('Validar certificado IMAP')->default(true),
                        TextInput::make('smtp_port')
                            ->label('Porta SMTP')
                            ->numeric()
                            ->default(465)
                            ->required()
                            ->helperText('Mesmo compartilhando servidor e credenciais, a porta de envio normalmente e diferente da porta IMAP.'),
                        TextInput::make('smtp_ehlo_domain')
                            ->label('Dominio EHLO/HELO')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Obrigatorio para o modulo de email do Filament. Informe o dominio que o cliente SMTP deve anunciar no EHLO/HELO, por exemplo erac61.com.br.'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
                Section::make('SMTP')
                    ->schema([
                        TextInput::make('smtp_host')
                            ->label('Host SMTP')
                            ->required(fn (Get $get): bool => ! $get('use_imap_credentials_for_smtp')),
                        TextInput::make('smtp_encryption')
                            ->label('Criptografia SMTP')
                            ->default('ssl')
                            ->required(fn (Get $get): bool => ! $get('use_imap_credentials_for_smtp')),
                        TextInput::make('smtp_username')
                            ->label('Usuario SMTP')
                            ->required(fn (Get $get): bool => ! $get('use_imap_credentials_for_smtp')),
                        TextInput::make('smtp_password')
                            ->label('Senha SMTP')
                            ->password()
                            ->revealable()
                            ->helperText('Na edicao, deixe em branco para manter a senha atual.')
                            ->autocomplete('new-password')
                            ->afterStateHydrated(fn ($component) => $component->state(''))
                            ->dehydrated(fn ($state): bool => filled($state)),
                    ])
                    ->hidden(fn (Get $get): bool => (bool) $get('use_imap_credentials_for_smtp'))
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
                Section::make('Pastas especiais')
                    ->schema([
                        TextInput::make('inbox_folder_name')->label('Entrada')->default('INBOX')->required(),
                        TextInput::make('sent_folder_name')->label('Enviados'),
                        TextInput::make('drafts_folder_name')->label('Rascunhos'),
                        TextInput::make('spam_folder_name')->label('Spam'),
                        TextInput::make('trash_folder_name')->label('Lixeira'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
            ]);
    }
}
