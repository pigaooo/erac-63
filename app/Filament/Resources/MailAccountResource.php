<?php

namespace App\Filament\Resources;

use App\Filament\Pages\Mailbox;
use App\Filament\Resources\MailAccountResource\Pages\CreateMailAccount;
use App\Filament\Resources\MailAccountResource\Pages\EditMailAccount;
use App\Filament\Resources\MailAccountResource\Pages\ListMailAccounts;
use App\Jobs\SyncMailAccountFolders;
use App\Jobs\SyncMailFolderMessages;
use App\Models\MailAccount;
use App\Support\Mail\ImapSyncService;
use App\Support\Mail\MailAccountManager;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Throwable;

class MailAccountResource extends Resource
{
    protected static ?string $model = MailAccount::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contas de email';

    protected static ?string $modelLabel = 'Conta de email';

    protected static ?string $pluralModelLabel = 'Contas de email';

    protected static string | \UnitEnum | null $navigationGroup = 'Email';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
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
                    ->columns(2),
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
                    ->columns(2),
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
                    ->columns(2),
                Section::make('Pastas especiais')
                    ->schema([
                        TextInput::make('inbox_folder_name')->label('Entrada')->default('INBOX')->required(),
                        TextInput::make('sent_folder_name')->label('Enviados'),
                        TextInput::make('drafts_folder_name')->label('Rascunhos'),
                        TextInput::make('spam_folder_name')->label('Spam'),
                        TextInput::make('trash_folder_name')->label('Lixeira'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function normalizeSharedSmtpData(array $data): array
    {
        $useSharedSmtp = $data['use_imap_credentials_for_smtp']
            ?? blank($data['smtp_host'] ?? null);

        if (! $useSharedSmtp) {
            unset($data['use_imap_credentials_for_smtp']);

            return $data;
        }

        $data['smtp_host'] = $data['imap_host'] ?? $data['smtp_host'] ?? null;
        $data['smtp_encryption'] = $data['imap_encryption'] ?? $data['smtp_encryption'] ?? null;
        $data['smtp_username'] = $data['imap_username'] ?? $data['smtp_username'] ?? null;

        if (filled($data['imap_password'] ?? null)) {
            $data['smtp_password'] = $data['imap_password'];
        }

        unset($data['use_imap_credentials_for_smtp']);

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Conta')->searchable()->sortable(),
                TextColumn::make('email_address')->label('Email')->searchable(),
                TextColumn::make('imap_host')->label('IMAP')->searchable(),
                TextColumn::make('smtp_host')->label('SMTP')->searchable()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')->label('Ativa')->boolean(),
                TextColumn::make('last_synced_at')->label('Ultima sincronizacao')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->recordActions([
                Action::make('mailbox')
                    ->label('Abrir caixa')
                    ->icon('heroicon-o-inbox')
                    ->url(fn (MailAccount $record): string => Mailbox::getUrl(['account' => $record->getKey()])),
                Action::make('testImap')
                    ->label('Testar IMAP')
                    ->icon('heroicon-o-server-stack')
                    ->action(function (MailAccount $record): void {
                        try {
                            app(ImapSyncService::class)->testConnection($record);
                        } catch (Throwable $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();

                            return;
                        }

                        Notification::make()->title('Configuracao IMAP validada.')->success()->send();
                    }),
                Action::make('testSmtp')
                    ->label('Testar SMTP')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (MailAccount $record): void {
                        try {
                            if (blank($record->smtp_ehlo_domain)) {
                                throw new \RuntimeException('Preencha o dominio EHLO/HELO da conta antes de testar o SMTP.');
                            }

                            app(MailAccountManager::class)->createMailer($record);
                        } catch (Throwable $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();

                            return;
                        }

                        Notification::make()->title('Configuracao SMTP validada.')->success()->send();
                    }),
                Action::make('syncFolders')
                    ->label('Sincronizar pastas')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (MailAccount $record): void {
                        try {
                            SyncMailAccountFolders::dispatch($record->getKey());
                        } catch (Throwable $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();

                            return;
                        }

                        Notification::make()->title('Sincronizacao de pastas enviada para a fila.')->success()->send();
                    }),
                Action::make('syncMessages')
                    ->label('Sincronizar mensagens')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (MailAccount $record): void {
                        try {
                            foreach ($record->folders()->where('is_active', true)->get() as $folder) {
                                SyncMailFolderMessages::dispatch($folder->getKey());
                            }
                        } catch (Throwable $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();

                            return;
                        }

                        Notification::make()->title('Sincronizacao de mensagens enviada para a fila.')->success()->send();
                    }),
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
            'index' => ListMailAccounts::route('/'),
            'create' => CreateMailAccount::route('/create'),
            'edit' => EditMailAccount::route('/{record}/edit'),
        ];
    }
}
