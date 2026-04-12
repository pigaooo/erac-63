<x-filament-panels::page>
    <div class="mailbox-shell space-y-4">
        <x-filament::section class="mailbox-toolbar-section">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">
                        Email workspace
                    </div>

                    <h1 class="text-2xl font-semibold text-gray-950 dark:text-white">
                        Caixa postal
                    </h1>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Visualize pastas sincronizadas, navegue pelas mensagens e responda sem sair do painel.
                    </p>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <label class="input input-bordered mailbox-account-input w-full min-w-[18rem] bg-white/80 dark:bg-white/[0.04] ring-2 ring-primary-500/10 shadow-sm" wire:loading.class="opacity-70" wire:target="syncNow">
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

                    <div class="join">
                        <x-filament::button wire:click="syncNow" wire:loading.attr="disabled" wire:target="syncNow" :disabled="$this->selectedAccount === null" class="join-item min-w-[11rem]">
                            <span class="inline-flex items-center gap-2">
                                <span wire:loading.remove wire:target="syncNow">Sincronizar</span>
                                <span wire:loading wire:target="syncNow">Sincronizando...</span>
                            </span>
                        </x-filament::button>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
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

        <div class="grid gap-4 xl:grid-cols-[15rem_minmax(0,1fr)]">
            <div class="space-y-4">
                <x-filament::section
                    heading="Pastas"
                    description="Explorer da conta sincronizada"
                    icon="heroicon-o-folder"
                    class="mailbox-column-section"
                >
                    @include('filament.pages.partials.mail-folders-panel', ['page' => $this])
                </x-filament::section>
            </div>

            <div class="space-y-4">
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
                        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
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
