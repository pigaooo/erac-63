<?php

namespace App\Filament\Pages;

use App\Jobs\SendMailFromAccount;
use App\Jobs\SyncMailAccountFolders;
use App\Jobs\SyncMailFolderMessages;
use App\Models\Inscrito;
use App\Models\MailAccount;
use App\Models\MailAttachment;
use App\Models\MailEvent;
use App\Models\MailFolder;
use App\Models\MailMessage;
use App\Support\Mail\ImapSyncService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class Mailbox extends Page implements HasForms, HasInfolists, HasTable
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'Gerenciador de emails';

    protected static string | \UnitEnum | null $navigationGroup = 'Email';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Gerenciador de emails';

    protected static ?string $slug = 'mailbox';

    protected string $view = 'filament.pages.mailbox';

    protected Width | string | null $maxContentWidth = Width::Full;

    public ?int $selectedAccountId = null;

    public ?int $selectedFolderId = null;

    public ?int $selectedMessageId = null;

    public ?int $moveDestinationId = null;

    public bool $showComposer = false;

    public string $messageSearch = '';

    /**
     * @var array<string, mixed>
     */
    public array $composerData = [];

    public string $composerRecipientInput = '';

    public ?string $composerInReplyTo = null;

    /**
     * @var array<int, string>
     */
    public array $composerReferences = [];

    public function mount(?int $account = null): void
    {
        $this->selectedAccountId = $account ?: MailAccount::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->value('id');

        $this->selectDefaultFolder();
        $this->resetComposer();
        $this->resetTable();
    }

    public function composerForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('composerData')
            ->components([
                TextInput::make('subject')
                    ->label('Assunto')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('body')
                    ->label('Mensagem')
                    ->fileAttachmentsDisk('local')
                    ->fileAttachmentsDirectory('private/mail/outgoing/inline')
                    ->toolbarButtons([
                        'bold', 'italic', 'underline', 'strike', 'link',
                        'h2', 'h3',
                        'blockquote', 'bulletList', 'orderedList',
                        'table', 'attachFiles',
                        'undo', 'redo',
                    ])
                    ->columnSpanFull()
                    ->required(),
                FileUpload::make('attachments')
                    ->label('Anexos')
                    ->multiple()
                    ->disk('local')
                    ->directory('private/mail/outgoing')
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
                Select::make('reply_context')
                    ->label('Contexto')
                    ->options([
                        'new' => 'Nova mensagem',
                        'reply' => 'Resposta',
                        'forward' => 'Encaminhamento',
                    ])
                    ->default('new')
                    ->disabled(),
            ])
            ->columns(2);
    }

    public function messageMetaInfolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->selectedMessage)
            ->columns(2)
            ->components([
                TextEntry::make('subject')
                    ->label('Assunto')
                    ->placeholder('(sem assunto)')
                    ->columnSpanFull()
                    ->weight('bold'),
                TextEntry::make('from_addresses')
                    ->label('De')
                    ->state(fn (): string => $this->formatAddressesForDisplay($this->selectedMessage?->from_addresses ?? []))
                    ->columnSpanFull(),
                TextEntry::make('to_addresses')
                    ->label('Para')
                    ->state(fn (): string => $this->formatInboundAddressForPrivacy(
                        $this->selectedMessage?->to_addresses ?? [],
                        $this->selectedMessage?->direction,
                    ))
                    ->columnSpanFull(),
                TextEntry::make('cc_addresses')
                    ->label('Cc')
                    ->state(fn (): string => $this->formatInboundAddressForPrivacy(
                        $this->selectedMessage?->cc_addresses ?? [],
                        $this->selectedMessage?->direction,
                    ))
                    ->placeholder('-'),
                TextEntry::make('received_at')
                    ->label('Recebido em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                IconEntry::make('is_seen')
                    ->label('Lido')
                    ->boolean(),
                IconEntry::make('has_attachments')
                    ->label('Anexos')
                    ->boolean(),
                RepeatableEntry::make('attachments')
                    ->label('Arquivos')
                    ->hidden(fn (): bool => $this->selectedMessage?->attachments->isEmpty() ?? true)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('filename')
                            ->label('Arquivo')
                            ->placeholder('anexo'),
                        TextEntry::make('content_type')
                            ->label('Tipo')
                            ->placeholder('arquivo'),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function foldersPanel(Schema $schema): Schema
    {
        return $schema->components([
        Section::make('Contas')
            ->icon('heroicon-o-envelope')
            ->description('Selecione a conta para visualizar as pastas e mensagens.')
            ->schema([
              Flex::make([
                Section::make('Pastas')
                    ->icon('heroicon-o-folder')
                    ->description('Explorer sincronizado com a estrutura remota da conta.')
                    ->schema([
                        View::make('filament.pages.partials.mail-folders-panel')
                            ->viewData(['page' => $this]),
                    ])
                    ->grow(false),
                Section::make($this->selectedFolder?->display_name ?? 'Mensagens')
                    ->icon('heroicon-o-inbox')
                    ->description($this->selectedAccount?->email_address ?? 'Selecione uma conta')
                    ->schema([
                        View::make('filament.pages.partials.mail-messages-panel')
                            ->viewData(['page' => $this]),
                    ])
                    ->grow(true),
            ])->from('md'),
        
            ])
            ->grow(false),    
        ]);
    }

    public function viewerPanel(Schema $schema): Schema
    {
        return $schema->components([
            Flex::make([
                Section::make($this->showComposer ? 'Composer' : 'Leitura')
                    ->icon(fn (): string => $this->showComposer ? 'heroicon-o-pencil-square' : 'heroicon-o-eye')
                    ->description($this->showComposer
                        ? 'Escreva em Markdown e envie pela conta selecionada.'
                        : 'Painel da mensagem selecionada.')
                    ->schema([
                        View::make('filament.pages.partials.mail-viewer-panel')
                            ->viewData(['page' => $this]),
                    ]),
            ])->from('md'),
        ]);
    }

    public function updatedSelectedAccountId(): void
    {
        $this->selectedFolderId = null;
        $this->selectedMessageId = null;
        $this->showComposer = false;
        $this->selectDefaultFolder();
        $this->resetComposer();
        $this->resetTable();
    }

    public function updatedSelectedFolderId(): void
    {
        $this->selectedMessageId = null;
        $this->moveDestinationId = null;
        $this->showComposer = false;
        $this->resetTable();
    }

    public function updatedMessageSearch(): void
    {
        $this->resetTable();
    }

    public function updatedComposerRecipientInput(string $value): void
    {
        $this->composerRecipientInput = $this->mergeComposerRecipientsFromInput($value);
    }

    public function commitComposerRecipientInput(): void
    {
        $this->composerRecipientInput = $this->mergeComposerRecipientsFromInput($this->composerRecipientInput, true);
    }

    public function removeComposerRecipient(string $address): void
    {
        $remainingRecipients = collect($this->parseAddressString((string) ($this->composerData['to'] ?? '')))
            ->reject(fn (array $recipient): bool => Str::lower($recipient['address']) === Str::lower($address))
            ->values()
            ->all();

        $this->composerData['to'] = $this->formatAddresses($remainingRecipients);
    }

    public function selectComposerSuggestion(string $email): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $inscrito = Inscrito::query()
            ->select(['name', 'email'])
            ->where('email', $email)
            ->first();

        $this->mergeComposerRecipients([[
            'address' => $email,
            'name' => filled($inscrito?->name) ? $inscrito->name : null,
        ]]);

        $this->composerRecipientInput = '';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                IconColumn::make('is_seen')
                    ->label('Lido')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-s-check' : 'heroicon-s-envelope')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                    ->alignCenter()
                    ->width('68px'),
                TextColumn::make('subject')
                    ->label('Assunto')
                    ->default('(sem assunto)')
                    ->limit(50)
                    ->tooltip(fn (MailMessage $record): string => $record->subject ?: '(sem assunto)')
                    ->description(fn (MailMessage $record): ?string => filled($record->snippet)
                        ? Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $record->snippet)) ?: ''), 88)
                        : null)
                    ->weight(fn (MailMessage $record): string => $record->is_seen ? 'medium' : 'bold'),
                TextColumn::make('from_addresses')
                    ->label('Remetente')
                    ->state(function (MailMessage $record): string {
                        $name = data_get($record->from_addresses, '0.name');
                        $address = data_get($record->from_addresses, '0.address');

                        if (filled($name) && filled($address)) {
                            return $name . ': ' . $address;
                        }

                        return $address ?: $name ?: 'Sem remetente';
                    })
                    ->limit(56)
                    ->tooltip(fn (MailMessage $record): string => data_get($record->from_addresses, '0.name')
                        && data_get($record->from_addresses, '0.address')
                        ? data_get($record->from_addresses, '0.name') . ': ' . data_get($record->from_addresses, '0.address')
                        : (data_get($record->from_addresses, '0.address')
                            ?: data_get($record->from_addresses, '0.name', 'Sem remetente'))),
                TextColumn::make('received_at')
                    ->label('Data')
                    ->state(fn (MailMessage $record): string => $record->received_at?->isToday()
                        ? $record->received_at->format('H:i')
                        : ($record->received_at?->format('d/m H:i') ?? '-'))
                    ->sortable(),
            ])
            ->defaultSort('received_at', 'desc')
            ->recordAction('openMessageFromTable')
            ->recordClasses(fn (MailMessage $record): string => $record->is_seen
                ? 'mailbox-message-row is-read'
                : 'mailbox-message-row is-unread')
            ->bulkActions([
                BulkAction::make('deleteSelectedMessages')
                    ->label('Deletar selecionadas')
                    ->icon('heroicon-o-trash')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (EloquentCollection $records, ImapSyncService $syncService): void {
                        if ($records->isEmpty()) {
                            return;
                        }

                        $junkFolder = $this->resolveJunkFolder();
                        $movedToJunk = 0;
                        $deletedPermanently = 0;

                        foreach ($records as $message) {
                            if (! $message instanceof MailMessage) {
                                continue;
                            }

                            $message->loadMissing('folder');

                            if ($this->isJunkFolder($message->folder)) {
                                $syncService->deleteMessagePermanently($message);
                                $deletedPermanently++;

                                continue;
                            }

                            if ($junkFolder === null || $junkFolder->getKey() === $message->mail_folder_id) {
                                continue;
                            }

                            $syncService->moveMessage($message, $junkFolder);
                            $movedToJunk++;
                        }

                        $titles = [];

                        if ($movedToJunk > 0) {
                            $titles[] = $movedToJunk . ' movida(s) para Junk';
                        }

                        if ($deletedPermanently > 0) {
                            $titles[] = $deletedPermanently . ' removida(s) permanentemente';
                        }

                        Notification::make()
                            ->title($titles !== [] ? implode(' e ', $titles) . '.' : 'Nenhuma mensagem foi alterada.')
                            ->success()
                            ->send();

                        $this->selectedMessageId = null;
                        $this->refreshMailboxCollections();
                    }),
                BulkAction::make('moveSelectedMessages')
                    ->label('Mover selecionadas')
                    ->icon('heroicon-o-folder')
                    ->color('gray')
                    ->form([
                        Select::make('destination_folder_id')
                            ->label('Pasta de destino')
                            ->required()
                            ->options(fn (): array => $this->moveDestinationOptions()),
                    ])
                    ->deselectRecordsAfterCompletion()
                    ->action(function (EloquentCollection $records, array $data, ImapSyncService $syncService): void {
                        if ($records->isEmpty()) {
                            return;
                        }

                        $destinationId = (int) ($data['destination_folder_id'] ?? 0);

                        if ($destinationId < 1) {
                            return;
                        }

                        $destination = MailFolder::query()
                            ->where('mail_account_id', $this->selectedAccountId)
                            ->where('is_active', true)
                            ->find($destinationId);

                        if ($destination === null) {
                            Notification::make()
                                ->title('Pasta de destino nao encontrada.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $movedCount = 0;

                        foreach ($records as $message) {
                            if (! $message instanceof MailMessage || $message->mail_folder_id === $destination->getKey()) {
                                continue;
                            }

                            $syncService->moveMessage($message, $destination);
                            $movedCount++;
                        }

                        Notification::make()
                            ->title($movedCount > 0
                                ? $movedCount . ' mensagem(ns) movida(s) para ' . $destination->display_name . '.'
                                : 'Nenhuma mensagem foi movida.')
                            ->success()
                            ->send();

                        $this->selectedMessageId = null;
                        $this->refreshMailboxCollections();
                    }),
            ])
            ->paginated([10, 15, 25, 50, 100, 200])
            ->defaultPaginationPageOption(15)
            ->stackedOnMobile()
            ->emptyStateHeading('Nenhuma mensagem encontrada nesta pasta.');
    }

    protected function getTableQuery(): Builder
    {
        return MailMessage::query()
            ->when(
                $this->selectedFolderId !== null,
                fn (Builder $query): Builder => $query->where('mail_folder_id', $this->selectedFolderId),
                fn (Builder $query): Builder => $query->whereRaw('1 = 0'),
            )
            ->when(
                filled($this->messageSearch),
                fn (Builder $query): Builder => $query->where(function (Builder $builder): void {
                    $search = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], trim($this->messageSearch)) . '%';

                    $builder
                        ->where('subject', 'like', $search)
                        ->orWhere('snippet', 'like', $search)
                        ->orWhere('from_addresses', 'like', $search)
                        ->orWhere('to_addresses', 'like', $search);
                }),
            )
            ->where('is_deleted', false);
    }

    public function getAccountsProperty(): Collection
    {
        return MailAccount::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getSelectedAccountProperty(): ?MailAccount
    {
        if ($this->selectedAccountId === null) {
            return null;
        }

        return MailAccount::query()->find($this->selectedAccountId);
    }

    public function getFoldersProperty(): Collection
    {
        if ($this->selectedAccountId === null) {
            return collect();
        }

        return MailFolder::query()
            ->withCount([
                'messages' => fn ($query) => $query->where('is_deleted', false),
                'messages as unread_messages_count' => fn ($query) => $query
                    ->where('is_deleted', false)
                    ->where('is_seen', false),
            ])
            ->where('mail_account_id', $this->selectedAccountId)
            ->where('is_active', true)
            ->orderByRaw("case when special_use = 'inbox' then 0 when special_use = 'sent' then 1 else 2 end")
            ->orderBy('remote_name')
            ->get();
    }

    public function getFolderTreeProperty(): array
    {
        $tree = [];

        foreach ($this->folders as $folder) {
            $delimiter = $folder->delimiter ?: '/';
            $segments = array_values(array_filter(explode($delimiter, $folder->remote_name)));
            $pointer = &$tree;

            foreach ($segments as $index => $segment) {
                $key = implode($delimiter, array_slice($segments, 0, $index + 1));

                if (! isset($pointer[$key])) {
                    $pointer[$key] = [
                        'folder' => null,
                        'label' => $segment,
                        'children' => [],
                    ];
                }

                if ($index === array_key_last($segments)) {
                    $pointer[$key]['folder'] = $folder;
                    $pointer[$key]['label'] = $this->folderLabel($folder);
                }

                $pointer = &$pointer[$key]['children'];
            }

            unset($pointer);
        }

        return array_values($tree);
    }

    public function getMessagesProperty(): Collection
    {
        if ($this->selectedFolderId === null) {
            return collect();
        }

        return MailMessage::query()
            ->where('mail_folder_id', $this->selectedFolderId)
            ->where('is_deleted', false)
            ->orderByDesc('received_at')
            ->orderByDesc('id')
            ->limit(80)
            ->get();
    }

    public function getSelectedFolderProperty(): ?MailFolder
    {
        if ($this->selectedFolderId === null) {
            return null;
        }

        return $this->folders->firstWhere('id', $this->selectedFolderId);
    }

    public function getSelectedMessageProperty(): ?MailMessage
    {
        if ($this->selectedMessageId === null) {
            return null;
        }

        return MailMessage::query()
            ->with(['attachments', 'folder', 'account'])
            ->find($this->selectedMessageId);
    }

    public function getRecentEventsProperty(): Collection
    {
        if ($this->selectedAccountId === null) {
            return collect();
        }

        return MailEvent::query()
            ->where('mail_account_id', $this->selectedAccountId)
            ->orderByDesc('occurred_at')
            ->limit(8)
            ->get();
    }

    public function getMoveDestinationsProperty(): Collection
    {
        return $this->folders
            ->where('id', '!=', $this->selectedFolderId)
            ->values();
    }

    public function getSelectedFolderMessageCountProperty(): int
    {
        return (int) ($this->selectedFolder?->messages_count ?? 0);
    }

    public function getSelectedFolderUnreadCountProperty(): int
    {
        return (int) ($this->selectedFolder?->unread_messages_count ?? 0);
    }

    /**
     * @return array<int, array{address:string,name:?string}>
     */
    public function getComposerRecipientsProperty(): array
    {
        return $this->parseAddressString((string) ($this->composerData['to'] ?? ''));
    }

    public function getComposerRecipientSuggestionsProperty(): Collection
    {
        $term = trim($this->composerRecipientInput);

        if (mb_strlen($term) < 2 || Str::contains($term, ['<', '>'])) {
            return collect();
        }

        $search = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term) . '%';
        $selectedAddresses = collect($this->composerRecipients)
            ->map(fn (array $recipient): string => Str::lower($recipient['address']))
            ->all();

        return Inscrito::query()
            ->select(['name', 'email'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where(function (Builder $query) use ($search): void {
                $query
                    ->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search);
            })
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->reject(fn (Inscrito $inscrito): bool => in_array(Str::lower((string) $inscrito->email), $selectedAddresses, true))
            ->map(fn (Inscrito $inscrito): array => [
                'name' => (string) $inscrito->name,
                'email' => (string) $inscrito->email,
            ])
            ->values();
    }

    public function syncNow(ImapSyncService $syncService): void
    {
        $account = $this->selectedAccount;

        if ($account === null) {
            return;
        }

        try {
            $folders = $syncService->runSafe(
                fn () => $syncService->syncFolders($account),
                $account,
                'sync_failed',
                'Falha ao sincronizar pastas da conta ' . $account->name . '.',
            );

            if ($this->selectedFolderId !== null) {
                $selectedFolder = $folders->firstWhere('id', $this->selectedFolderId)
                    ?? MailFolder::query()->find($this->selectedFolderId);

                if ($selectedFolder !== null) {
                    $syncService->runSafe(
                        fn () => $syncService->syncFolderMessages($selectedFolder),
                        $account,
                        'sync_failed',
                        'Falha ao sincronizar mensagens da pasta ' . $selectedFolder->display_name . '.',
                    );
                }
            }
        } catch (Throwable $exception) {
            $this->notifyFailure($exception);

            return;
        }

        $this->refreshMailboxCollections();

        Notification::make()
            ->title('Sincronizacao concluida.')
            ->success()
            ->send();
    }

    public function openFolder(int $folderId): void
    {
        if ($this->selectedFolderId === $folderId && $this->selectedMessageId === null && ! $this->showComposer) {
            return;
        }

        $this->selectedFolderId = $folderId;
        $this->selectedMessageId = null;
        $this->moveDestinationId = null;
        $this->showComposer = false;
        $this->resetTable();
    }

    public function openMessageFromTable(MailMessage $record, ImapSyncService $syncService): void
    {
        $this->openMessage($record->getKey(), $syncService);
    }

    public function openMessage(int $messageId, ImapSyncService $syncService): void
    {
        $message = MailMessage::query()->findOrFail($messageId);

        try {
            if ($message->html_body === null && $message->text_body === null) {
                $message = $syncService->hydrateMessage($message);
            }

            if (! $message->is_seen) {
                $syncService->markMessageSeen($message, true);
            }
        } catch (Throwable $exception) {
            $this->notifyFailure($exception);

            return;
        }

        $this->showComposer = false;
        $this->selectedMessageId = $message->getKey();
    }

    public function markSelectedMessageSeen(bool $seen, ImapSyncService $syncService): void
    {
        $message = $this->selectedMessage;

        if ($message === null) {
            return;
        }

        try {
            $syncService->markMessageSeen($message, $seen);
        } catch (Throwable $exception) {
            $this->notifyFailure($exception);

            return;
        }

        $this->selectedMessageId = $message->getKey();
    }

    public function moveSelectedMessage(ImapSyncService $syncService): void
    {
        $message = $this->selectedMessage;

        if ($message === null || $this->moveDestinationId === null) {
            return;
        }

        $destination = MailFolder::query()->findOrFail($this->moveDestinationId);

        try {
            $syncService->moveMessage($message, $destination);
        } catch (Throwable $exception) {
            $this->notifyFailure($exception);

            return;
        }

        $this->selectedMessageId = null;
        $this->moveDestinationId = null;

        $this->refreshMailboxCollections();

        Notification::make()
            ->title('Mensagem movida.')
            ->success()
            ->send();
    }

    public function downloadAttachment(int $attachmentId)
    {
        $attachment = MailAttachment::query()
            ->whereHas('message', function (Builder $query): void {
                $query->whereKey($this->selectedMessageId);
            })
            ->findOrFail($attachmentId);

        if (! filled($attachment->path) || ! Storage::disk('local')->exists($attachment->path)) {
            Notification::make()
                ->title('Arquivo do anexo nao esta disponivel para download.')
                ->danger()
                ->send();

            return null;
        }

        return response()->download(
            Storage::disk('local')->path($attachment->path),
            $attachment->filename ?: 'anexo'
        );
    }

    public function composeNew(): void
    {
        $this->showComposer = true;
        $this->selectedMessageId = null;
        $this->resetComposer();
    }

    public function closeComposer(): void
    {
        $this->showComposer = false;
        $this->resetComposer();
    }

    public function backToMessageList(): void
    {
        $this->showComposer = false;
        $this->selectedMessageId = null;
        $this->moveDestinationId = null;
    }

    public function replyToSelectedMessage(): void
    {
        $message = $this->selectedMessage;

        if ($message === null) {
            return;
        }

        $this->showComposer = true;
        $this->composerInReplyTo = $message->remote_message_id;
        $this->composerReferences = array_values(array_filter([
            $message->headers['references'] ?? null,
            $message->remote_message_id,
        ]));
        $this->composerRecipientInput = '';

        $this->composerForm->fill([
            'to' => $this->formatAddresses($message->from_addresses ?? []),
            'subject' => Str::startsWith((string) $message->subject, 'Re:') ? (string) $message->subject : 'Re: ' . $message->subject,
            'body' => $this->buildReplyMarkdown($message),
            'attachments' => [],
            'reply_context' => 'reply',
        ]);
    }

    public function forwardSelectedMessage(): void
    {
        $message = $this->selectedMessage;

        if ($message === null) {
            return;
        }

        $this->showComposer = true;
        $this->composerInReplyTo = null;
        $this->composerReferences = [];
        $this->composerRecipientInput = '';
        $this->composerForm->fill([
            'to' => '',
            'subject' => Str::startsWith((string) $message->subject, 'Fwd:') ? (string) $message->subject : 'Fwd: ' . $message->subject,
            'body' => $this->buildForwardMarkdown($message),
            'attachments' => [],
            'reply_context' => 'forward',
        ]);
    }

    public function sendComposer(): void
    {
        if ($this->selectedAccountId === null) {
            return;
        }

        $this->composerRecipientInput = $this->mergeComposerRecipientsFromInput($this->composerRecipientInput, true);

        if ($this->composerRecipientInput !== '') {
            Notification::make()
                ->title('Revise o campo Para e informe apenas destinatarios validos.')
                ->danger()
                ->send();

            return;
        }

        $recipients = $this->composerRecipients;

        if ($recipients === []) {
            Notification::make()
                ->title('Informe ao menos um destinatario valido.')
                ->danger()
                ->send();

            return;
        }

        $data = $this->composerForm->getState();
        $body = trim((string) ($data['body'] ?? ''));
        $attachmentPayload = [];

        foreach ((array) ($data['attachments'] ?? []) as $path) {
            if (! is_string($path) || blank($path)) {
                continue;
            }

            $attachmentPayload[] = [
                'path' => $path,
                'name' => basename($path),
            ];
        }

        foreach ($recipients as $recipient) {
            SendMailFromAccount::dispatch(
                $this->selectedAccountId,
                [$recipient],
                (string) ($data['subject'] ?? ''),
                $body !== '' ? $body : null,
                $body !== '' ? strip_tags($body) : null,
                [],
                [],
                [],
                $attachmentPayload,
                $this->composerInReplyTo,
                $this->composerReferences,
            );
        }

       Notification::make()
        ->title(count($recipients) > 1 ? 'Envios colocados na fila.' : 'Envio colocado na fila.')
        ->body('Os emails foram enviados para a fila e serão processados em até 5 minutos.')
        ->success()
        ->send()
        ->duration(12000);

        $this->showComposer = false;
        $this->resetComposer();
    }

    private function selectDefaultFolder(): void
    {
        if ($this->selectedAccountId === null) {
            return;
        }

        $folder = MailFolder::query()
            ->where('mail_account_id', $this->selectedAccountId)
            ->where('is_active', true)
            ->orderByRaw("case when special_use = 'inbox' then 0 when special_use = 'sent' then 1 else 2 end")
            ->orderBy('display_name')
            ->first();

        $this->selectedFolderId = $folder?->getKey();
    }

    private function resetComposer(): void
    {
        $this->composerData = $this->defaultComposerData();
        $this->composerRecipientInput = '';
        $this->composerInReplyTo = null;
        $this->composerReferences = [];

        if ($this->hasCachedSchema('composerForm') || method_exists($this, 'composerForm')) {
            $this->composerForm->fill($this->composerData);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function defaultComposerData(): array
    {
        return [
            'to' => '',
            'subject' => '',
            'body' => '',
            'attachments' => [],
            'reply_context' => 'new',
        ];
    }

    /**
     * @param  array<int, array{address:string,name:?string}>  $addresses
     */
    private function formatAddresses(array $addresses): string
    {
        return implode(', ', array_map(
            fn (array $item): string => $item['name']
                ? sprintf('%s <%s>', $item['name'], $item['address'])
                : $item['address'],
            $addresses,
        ));
    }

    /**
     * @param  array<int, array{address:string,name:?string}>  $addresses
     */
    private function formatAddressesForDisplay(array $addresses): string
    {
        $formatted = $this->formatAddresses($addresses);

        return $formatted !== '' ? $formatted : '-';
    }

    /**
     * @param  array<int, array{address:string,name:?string}>  $addresses
     */
    private function formatInboundAddressForPrivacy(array $addresses, ?string $direction): string
    {
        if ($addresses === []) {
            return '-';
        }

        if ($direction !== 'inbound') {
            return $this->formatAddressesForDisplay($addresses);
        }

        $first = [$addresses[0]];
        $hiddenCount = max(count($addresses) - 1, 0);
        $visible = $this->formatAddresses($first);

        if ($hiddenCount <= 0) {
            return $visible !== '' ? $visible : '-';
        }

        return sprintf('%s (+%d oculto%s)', $visible, $hiddenCount, $hiddenCount > 1 ? 's' : '');
    }

    /**
     * @param  array<int, array{address:string,name:?string}>  $addresses
     */
    public function presentAddresses(array $addresses): string
    {
        return $this->formatAddressesForDisplay($addresses);
    }

    public function attachmentTypeLabel(?string $contentType, ?string $filename = null): string
    {
        $contentType = strtolower((string) $contentType);
        $extension = strtolower(pathinfo((string) $filename, PATHINFO_EXTENSION));

        return match (true) {
            Str::startsWith($contentType, 'image/') => 'Imagem',
            Str::startsWith($contentType, 'video/') => 'Video',
            Str::startsWith($contentType, 'audio/') => 'Audio',
            $contentType === 'application/pdf' || $extension === 'pdf' => 'PDF',
            in_array($extension, ['doc', 'docx'], true) => 'Documento Word',
            in_array($extension, ['xls', 'xlsx', 'csv'], true) => 'Planilha',
            in_array($extension, ['ppt', 'pptx'], true) => 'Apresentacao',
            in_array($extension, ['zip', 'rar', '7z', 'tar', 'gz'], true) => 'Arquivo compactado',
            in_array($extension, ['txt', 'md', 'rtf'], true) => 'Texto',
            filled($extension) => strtoupper($extension),
            default => 'Arquivo',
        };
    }

    public function attachmentIcon(?string $contentType, ?string $filename = null): string
    {
        $contentType = strtolower((string) $contentType);
        $extension = strtolower(pathinfo((string) $filename, PATHINFO_EXTENSION));

        return match (true) {
            Str::startsWith($contentType, 'image/') => 'heroicon-o-photo',
            Str::startsWith($contentType, 'video/') => 'heroicon-o-film',
            Str::startsWith($contentType, 'audio/') => 'heroicon-o-musical-note',
            $contentType === 'application/pdf' || $extension === 'pdf' => 'heroicon-o-document-text',
            in_array($extension, ['doc', 'docx', 'txt', 'md', 'rtf'], true) => 'heroicon-o-document',
            in_array($extension, ['xls', 'xlsx', 'csv'], true) => 'heroicon-o-table-cells',
            in_array($extension, ['zip', 'rar', '7z', 'tar', 'gz'], true) => 'heroicon-o-archive-box',
            default => 'heroicon-o-paper-clip',
        };
    }

    public function attachmentSizeLabel(?int $size): string
    {
        if (! $size || $size < 1) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $value = $size;
        $unitIndex = 0;

        while ($value >= 1024 && $unitIndex < count($units) - 1) {
            $value /= 1024;
            $unitIndex++;
        }

        $precision = $value >= 10 || $unitIndex === 0 ? 0 : 1;

        return number_format($value, $precision, ',', '.') . ' ' . $units[$unitIndex];
    }

    public function attachmentThumbnailDataUrl(?string $path, ?string $contentType, ?int $size = null): ?string
    {
        if (! filled($path) || ! Str::startsWith(strtolower((string) $contentType), 'image/')) {
            return null;
        }

        if (($size ?? 0) > (2 * 1024 * 1024)) {
            return null;
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($path)) {
            return null;
        }

        try {
            $content = $disk->get($path);
        } catch (Throwable) {
            return null;
        }

        return 'data:' . ($contentType ?: 'image/jpeg') . ';base64,' . base64_encode($content);
    }

    /**
     * @return array<int, array{address:string,name:?string}>
     */
    private function parseAddressString(string $value): array
    {
        $parts = array_filter(array_map('trim', explode(',', $value)));

        return array_values(array_filter(array_map(function (string $part): ?array {
            if (preg_match('/^(.*)<(.+)>$/', $part, $matches) === 1) {
                $address = trim($matches[2]);
                $name = trim(trim($matches[1]), '" ');
            } else {
                $address = $part;
                $name = null;
            }

            if (! filter_var($address, FILTER_VALIDATE_EMAIL)) {
                return null;
            }

            return [
                'address' => $address,
                'name' => $name !== '' ? $name : null,
            ];
        }, $parts)));
    }

    private function mergeComposerRecipientsFromInput(string $input, bool $finalize = false): string
    {
        $normalized = str_replace(["\r\n", "\n", ';'], ',', $input);
        $hasTrailingDelimiter = preg_match('/,\s*$/', $normalized) === 1;
        $segments = array_values(array_filter(array_map('trim', explode(',', $normalized)), fn (string $segment): bool => $segment !== ''));
        $pending = '';

        if (! $finalize && ! $hasTrailingDelimiter && $segments !== []) {
            $pending = array_pop($segments) ?? '';
        }

        $validRecipients = [];
        $invalidSegments = [];

        foreach ($segments as $segment) {
            $parsedRecipients = $this->parseAddressString($segment);

            if ($parsedRecipients === []) {
                $invalidSegments[] = $segment;

                continue;
            }

            array_push($validRecipients, ...$parsedRecipients);
        }

        if ($validRecipients !== []) {
            $this->mergeComposerRecipients($validRecipients);
        }

        return implode(', ', array_filter([...$invalidSegments, $pending]));
    }

    /**
     * @param  array<int, array{address:string,name:?string}>  $recipients
     */
    private function mergeComposerRecipients(array $recipients): void
    {
        $existing = $this->parseAddressString((string) ($this->composerData['to'] ?? ''));
        $merged = collect([...$existing, ...$recipients])
            ->filter(fn (array $recipient): bool => filled($recipient['address']))
            ->unique(fn (array $recipient): string => Str::lower($recipient['address']))
            ->values()
            ->all();

        $this->composerData['to'] = $this->formatAddresses($merged);
    }

    private function buildReplyMarkdown(MailMessage $message): string
    {
        $header = [
            '---',
            '**Mensagem original**',
            'De: ' . $this->formatAddressesForDisplay($message->from_addresses ?? []),
            'Recebido em: ' . ($message->received_at?->format('d/m/Y H:i') ?: '-'),
            'Assunto: ' . ($message->subject ?: '(sem assunto)'),
            '',
        ];

        return "\n\n" . implode("\n", $header) . $this->quoteMarkdown($message);
    }

    private function buildForwardMarkdown(MailMessage $message): string
    {
        $header = [
            '---',
            '**Encaminhado**',
            'De: ' . $this->formatAddressesForDisplay($message->from_addresses ?? []),
            'Para: ' . $this->formatAddressesForDisplay($message->to_addresses ?? []),
            'Recebido em: ' . ($message->received_at?->format('d/m/Y H:i') ?: '-'),
            'Assunto: ' . ($message->subject ?: '(sem assunto)'),
            '',
        ];

        return "\n\n" . implode("\n", $header) . $this->quoteMarkdown($message);
    }

    private function quoteMarkdown(MailMessage $message): string
    {
        $body = trim((string) ($message->text_body ?: strip_tags((string) $message->html_body)));

        if ($body === '') {
            return '';
        }

        return collect(preg_split('/\R/', $body) ?: [])
            ->map(fn (string $line): string => '> ' . $line)
            ->implode("\n");
    }

    private function folderLabel(MailFolder $folder): string
    {
        $delimiter = $folder->delimiter ?: '/';
        $segments = array_values(array_filter(explode($delimiter, $folder->display_name ?: $folder->remote_name)));

        return $segments !== [] ? end($segments) : ($folder->display_name ?: $folder->remote_name);
    }

    public function folderIcon(MailFolder $folder): string
    {
        $label = Str::lower($folder->display_name ?: $folder->remote_name);

        return match (true) {
            $folder->special_use === 'inbox',
            Str::contains($label, ['inbox', 'entrada', 'caixa de entrada']) => 'heroicon-o-inbox-stack',

            $folder->special_use === 'sent',
            Str::contains($label, ['sent', 'enviados', 'enviadas', 'saida']) => 'heroicon-o-paper-airplane',

            $folder->special_use === 'drafts',
            Str::contains($label, ['draft', 'rascunho']) => 'heroicon-o-pencil-square',

            $folder->special_use === 'spam',
            Str::contains($label, ['spam', 'junk', 'lixo eletronico']) => 'heroicon-o-no-symbol',

            $folder->special_use === 'trash',
            Str::contains($label, ['trash', 'lixeira', 'deleted']) => 'heroicon-o-trash',

            Str::contains($label, ['archive', 'arquivo', 'arquivados']) => 'heroicon-o-archive-box',
            Str::contains($label, ['important', 'importante']) => 'heroicon-o-bookmark-square',
            Str::contains($label, ['star', 'favorito', 'flag']) => 'heroicon-o-star',
            Str::contains($label, ['notification', 'notificacao', 'alerta']) => 'heroicon-o-bell',
            Str::contains($label, ['finance', 'financeiro', 'billing', 'invoice']) => 'heroicon-o-banknotes',
            Str::contains($label, ['support', 'suporte', 'atendimento']) => 'heroicon-o-lifebuoy',
            Str::contains($label, ['marketing', 'newsletter', 'campanha']) => 'heroicon-o-megaphone',
            Str::contains($label, ['team', 'equipe', 'interno']) => 'heroicon-o-users',
            Str::contains($label, ['attachment', 'anexo', 'files']) => 'heroicon-o-paper-clip',

            default => $folder->is_selectable ? 'heroicon-o-folder' : 'heroicon-o-folder-open',
        };
    }

    private function notifyFailure(Throwable $exception): void
    {
        Notification::make()
            ->title(Str::limit($exception->getMessage(), 220))
            ->danger()
            ->send();
    }

    private function refreshMailboxCollections(): void
    {
        unset($this->folders, $this->folderTree, $this->selectedFolder, $this->moveDestinations, $this->messages);

        $this->resetTable();
    }

    private function resolveJunkFolder(): ?MailFolder
    {
        if ($this->selectedAccountId === null) {
            return null;
        }

        $folders = $this->folders;

        $junkBySpecialUse = $folders->first(function (MailFolder $folder): bool {
            return in_array($folder->special_use, ['spam', 'trash'], true);
        });

        if ($junkBySpecialUse !== null) {
            return $junkBySpecialUse;
        }

        return $folders->first(function (MailFolder $folder): bool {
            $label = Str::lower($folder->display_name ?: $folder->remote_name);

            return Str::contains($label, ['junk', 'spam', 'lixo', 'trash', 'lixeira']);
        });
    }

    private function isJunkFolder(?MailFolder $folder): bool
    {
        if ($folder === null) {
            return false;
        }

        if (in_array($folder->special_use, ['spam', 'trash'], true)) {
            return true;
        }

        $label = Str::lower($folder->display_name ?: $folder->remote_name);

        return Str::contains($label, ['junk', 'spam', 'lixo', 'trash', 'lixeira']);
    }

    /**
     * @return array<int, string>
     */
    private function moveDestinationOptions(): array
    {
        if ($this->selectedAccountId === null) {
            return [];
        }

        return $this->folders
            ->where('is_active', true)
            ->where('is_selectable', true)
            ->mapWithKeys(fn (MailFolder $folder): array => [$folder->getKey() => $folder->display_name])
            ->all();
    }

    public function renderedSelectedMessageBody(): HtmlString
    {
        $message = $this->selectedMessage;

        if ($message === null) {
            return new HtmlString('');
        }

        $htmlBody = trim((string) ($message->html_body ?? ''));

        if ($htmlBody !== '') {
            return new HtmlString($htmlBody);
        }

        $textBodyRaw = trim((string) ($message->text_body ?? ''));

        if ($textBodyRaw !== '') {
            $decodedText = html_entity_decode($textBodyRaw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $htmlCandidate = Str::contains($decodedText, '<') ? $decodedText : $textBodyRaw;

            if (preg_match('/<\s*(table|tbody|tr|td|th|p|div|br|ul|ol|li|h[1-6])\b/i', $htmlCandidate) === 1) {
                return new HtmlString($htmlCandidate);
            }
        }

        $textBody = e((string) $message->text_body);

        return new HtmlString('<pre class="whitespace-pre-wrap font-sans text-sm leading-6 text-gray-700 dark:text-gray-200">' . $textBody . '</pre>');
    }
}
