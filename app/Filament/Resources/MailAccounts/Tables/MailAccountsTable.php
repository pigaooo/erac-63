<?php

namespace App\Filament\Resources\MailAccounts\Tables;

use App\Filament\Resources\MailAccounts\MailAccountResource;
use App\Models\MailAccount;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MailAccountsTable
{
    public static function configure(Table $table): Table
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
                    ->url(fn (MailAccount $record): string => MailAccountResource::getMailboxUrl($record)),
                Action::make('testImap')
                    ->label('Testar IMAP')
                    ->icon('heroicon-o-server-stack')
                    ->action(fn (MailAccount $record) => MailAccountResource::sendTestImapNotification($record)),
                Action::make('testSmtp')
                    ->label('Testar SMTP')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(fn (MailAccount $record) => MailAccountResource::sendTestSmtpNotification($record)),
                Action::make('syncFolders')
                    ->label('Sincronizar pastas')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn (MailAccount $record) => MailAccountResource::queueFolderSync($record)),
                Action::make('syncMessages')
                    ->label('Sincronizar mensagens')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (MailAccount $record) => MailAccountResource::queueMessageSync($record)),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
