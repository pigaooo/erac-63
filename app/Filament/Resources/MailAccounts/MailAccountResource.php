<?php

namespace App\Filament\Resources\MailAccounts;

use App\Filament\Pages\Mailbox;
use App\Filament\Resources\MailAccounts\Pages\CreateMailAccount;
use App\Filament\Resources\MailAccounts\Pages\EditMailAccount;
use App\Filament\Resources\MailAccounts\Pages\ListMailAccounts;
use App\Filament\Resources\MailAccounts\Schemas\MailAccountForm;
use App\Filament\Resources\MailAccounts\Tables\MailAccountsTable;
use App\Jobs\SyncMailAccountFolders;
use App\Jobs\SyncMailFolderMessages;
use App\Models\MailAccount;
use App\Support\Mail\ImapSyncService;
use App\Support\Mail\MailAccountManager;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Throwable;

class MailAccountResource extends Resource
{
    protected static ?string $model = MailAccount::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contas de email';

    protected static ?string $modelLabel = 'Conta de email';

    protected static ?string $pluralModelLabel = 'Contas de email';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistema';

    protected static ?string $navigationParentItem = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MailAccountForm::configure($schema);
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
        return MailAccountsTable::configure($table);
    }

    public static function getMailboxUrl(MailAccount $record): string
    {
        return Mailbox::getUrl(['account' => $record->getKey()]);
    }

    public static function sendTestImapNotification(MailAccount $record): void
    {
        try {
            app(ImapSyncService::class)->testConnection($record);
        } catch (Throwable $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();

            return;
        }

        Notification::make()->title('Configuracao IMAP validada.')->success()->send();
    }

    public static function sendTestSmtpNotification(MailAccount $record): void
    {
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
    }

    public static function queueFolderSync(MailAccount $record): void
    {
        try {
            SyncMailAccountFolders::dispatch($record->getKey());
        } catch (Throwable $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();

            return;
        }

        Notification::make()->title('Sincronizacao de pastas enviada para a fila.')->success()->send();
    }

    public static function queueMessageSync(MailAccount $record): void
    {
        try {
            foreach ($record->folders()->where('is_active', true)->get() as $folder) {
                SyncMailFolderMessages::dispatch($folder->getKey());
            }
        } catch (Throwable $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();

            return;
        }

        Notification::make()->title('Sincronizacao de mensagens enviada para a fila.')->success()->send();
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
