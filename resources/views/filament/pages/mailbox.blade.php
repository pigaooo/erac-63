<x-filament-panels::page>
    <div
        x-data="{ mobileFoldersOpen: false }"
        x-on:keydown.escape.window="mobileFoldersOpen = false"
        x-on:mailbox-folder-selected.window="mobileFoldersOpen = false"
        class="mailbox-shell space-y-4"
    >
        <x-filament::section class="mailbox-toolbar-section">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">
                        Gerenciador de emails
                    </div>

                    <h1 class="text-2xl font-semibold text-gray-950 dark:text-white">
                        Gerenciador de emails
                    </h1>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Gerencie contas, pastas e mensagens com uma experiencia consistente em desktop e mobile.
                    </p>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <label class="input input-bordered mailbox-account-input w-full min-w-0 md:min-w-[18rem] bg-white/80 dark:bg-white/[0.04] ring-2 ring-primary-500/10 shadow-sm" wire:loading.class="opacity-70" wire:target="syncNow">
                        <x-filament::icon icon="heroicon-o-envelope" class="h-4 w-4 text-gray-400" />

                        <select
                            wire:model.live="selectedAccountId"
                            wire:loading.attr="disabled"
                            wire:target="syncNow"
                            class="w-full bg-transparent text-sm focus:outline-none"
                        >
                            <option value="">Selecione uma conta</option>
                            @foreach ($this->accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->email_address }})</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex flex-col gap-2 sm:flex-row">
                        <x-filament::button
                            type="button"
                            color="gray"
                            icon="heroicon-o-folder"
                            class="xl:hidden"
                            x-on:click="mobileFoldersOpen = true"
                        >
                            Pastas
                        </x-filament::button>

                        <x-filament::button wire:click="syncNow" wire:loading.attr="disabled" wire:target="syncNow" :disabled="$this->selectedAccount === null" class="min-w-[11rem]">
                            <span class="inline-flex items-center gap-2">
                                <span wire:loading.remove wire:target="syncNow">Sincronizar</span>
                                <span wire:loading wire:target="syncNow">Sincronizando...</span>
                            </span>
                        </x-filament::button>
                    </div>
                </div>
            </div>

            <div class="mt-4 hidden flex-wrap gap-2 sm:flex">
                <span class="badge badge-ghost mailbox-stat-badge">
                    Conta: {{ $this->selectedAccount?->email_address ?? 'Nenhuma selecionada' }}
                </span>

                <span class="badge badge-ghost mailbox-stat-badge">
                    Pasta: {{ $this->selectedFolder?->display_name ?? 'Sem pasta' }}
                </span>

                <span class="badge badge-ghost mailbox-stat-badge">
                    Mensagens: {{ $this->selectedFolderMessageCount }}
                </span>

                <span class="badge badge-warning badge-outline mailbox-stat-badge">
                    Nao lidas: {{ $this->selectedFolderUnreadCount }}
                </span>
            </div>
        </x-filament::section>

        <div
            x-cloak
            x-show="mobileFoldersOpen"
            x-transition.opacity
            class="mailbox-mobile-sheet-overlay fixed inset-0 z-40 bg-slate-950/70 xl:hidden"
            x-on:click="mobileFoldersOpen = false"
        ></div>

        <div
            x-cloak
            x-show="mobileFoldersOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-full opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-full opacity-0"
            class="mailbox-mobile-sheet fixed inset-x-0 bottom-0 z-50 max-h-[78vh] overflow-y-auto rounded-t-[1.75rem] border border-white/10 bg-slate-950/95 p-4 shadow-2xl xl:hidden"
        >
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-white">Pastas</div>
                    <div class="text-xs text-slate-400">Selecione uma pasta para atualizar a lista.</div>
                </div>

                <x-filament::button type="button" color="gray" size="sm" x-on:click="mobileFoldersOpen = false" icon="heroicon-o-x-mark">
                    Fechar
                </x-filament::button>
            </div>

            @include('filament.pages.partials.mail-folders-panel', ['page' => $this])
        </div>

        <div class="grid gap-4 xl:grid-cols-[15rem_minmax(0,1fr)]">
            <div class="hidden space-y-4 xl:block">
                <x-filament::section
                    heading="Pastas"
                    description="Explorer da conta sincronizada"
                    icon="heroicon-o-folder"
                    class="mailbox-column-section"
                >
                    @include('filament.pages.partials.mail-folders-panel', ['page' => $this])
                </x-filament::section>
            </div>

            <div class="space-y-4 min-w-0">
                @if ($this->showComposer || $this->selectedMessage)
                    <x-filament::section
                        :heading="$this->showComposer ? 'Nova mensagem' : ($this->selectedMessage?->subject ?: 'Leitura da mensagem')"
                        :description="$this->showComposer ? 'Componha e envie pela conta selecionada.' : 'Visualizador com metadados, corpo e acoes da mensagem.'"
                        :icon="$this->showComposer ? 'heroicon-o-pencil-square' : 'heroicon-o-eye'"
                        class="mailbox-column-section mailbox-reader-section"
                    >
                        @include('filament.pages.partials.mail-viewer-panel', ['page' => $this])
                    </x-filament::section>
                @else
                    @php
                        $accountName = $this->selectedAccount?->name ?? $this->selectedAccount?->email_address ?? 'Nenhuma selecionada';
                        $folderLabel = $this->selectedFolder?->display_name ?? '';
                        $remoteName = strtolower($this->selectedFolder?->remote_name ?? $folderLabel);
                        $isInbox = str_contains($remoteName, 'inbox') || strtolower($folderLabel) === 'inbox';
                        $messagesHeading = $isInbox ? ('Email selecionado: ' . $accountName) : ($folderLabel ?: 'Mensagens');
                        $messagesDescription = $this->selectedAccount?->email_address ?? 'Selecione uma conta para carregar a lista';
                    @endphp

                    <x-filament::section
                        :heading="$messagesHeading"
                        :description="$messagesDescription"
                        icon="heroicon-o-inbox-stack"
                        class="mailbox-column-section"
                    >
                        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <label class="input input-bordered mailbox-search-input w-full bg-white/80 dark:bg-white/[0.04]">
                                <x-filament::icon icon="heroicon-o-magnifying-glass" class="h-4 w-4 text-gray-400" />

                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="messageSearch"
                                    placeholder="Buscar por assunto, remetente ou trecho"
                                    class="w-full bg-transparent text-sm focus:outline-none"
                                />
                            </label>

                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span class="badge badge-ghost mailbox-stat-badge">Total {{ $this->selectedFolderMessageCount }}</span>
                                <span class="badge badge-ghost mailbox-stat-badge">Nao lidas {{ $this->selectedFolderUnreadCount }}</span>
                            </div>
                        </div>

                        @include('filament.pages.partials.mail-messages-panel', ['page' => $this])
                    </x-filament::section>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
