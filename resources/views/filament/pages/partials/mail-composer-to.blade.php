@php /** @var \App\Filament\Pages\Mailbox $page */ @endphp

<div class="space-y-3">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <div class="text-sm font-medium text-gray-900 dark:text-white">Para</div>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Separe emails por vírgula ou use Enter. No mobile, utilize o botão "Adicionar" abaixo. Inscritos cadastrados aparecem como sugestão enquanto você digita.
            </p>
        </div>

        @if ($page->composerRecipients !== [])
            <span class="badge badge-ghost mailbox-stat-badge">
                {{ count($page->composerRecipients) }} destinatario(s)
            </span>
        @endif
    </div>

    <div class="rounded-[1.25rem] border border-gray-200/80 bg-gray-50/80 p-4 dark:border-white/10 dark:bg-white/[0.03]">
        @if ($page->composerRecipients !== [])
            <div class="mb-3 flex flex-wrap gap-2">
                @foreach ($page->composerRecipients as $recipient)
                    <span
                        class="inline-flex max-w-full items-center gap-2 rounded-full border border-primary-200/70 bg-primary-50 px-3 py-1 text-sm font-medium text-primary-700 dark:border-primary-500/20 dark:bg-primary-500/10 dark:text-primary-200"
                        title="{{ $recipient['name'] ? $recipient['name'] . ' <' . $recipient['address'] . '>' : $recipient['address'] }}"
                    >
                        <span class="truncate">{{ $recipient['address'] }}</span>

                        <button
                            type="button"
                            wire:click='removeComposerRecipient(@json($recipient["address"]))'
                            class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-primary-500/10 text-primary-700 transition hover:bg-primary-500/20 dark:text-primary-100"
                            aria-label="Remover destinatario"
                        >
                            <x-filament::icon icon="heroicon-o-x-mark" class="h-3.5 w-3.5" />
                        </button>
                    </span>
                @endforeach
            </div>
        @endif

        <label class="input input-bordered mailbox-account-input w-full bg-white/80 dark:bg-white/[0.04]">
            <x-filament::icon icon="heroicon-o-users" class="h-4 w-4 text-gray-400" />

            <input
                type="text"
                wire:model.live.debounce.150ms="composerRecipientInput"
                wire:keydown.enter.prevent="commitComposerRecipientInput"
                placeholder="Digite um email ou nome do inscrito"
                class="w-full bg-transparent text-sm focus:outline-none"
                autocomplete="off"
            />
        </label>

        <div class="mt-3 md:hidden">
            <x-filament::button
                type="button"
                wire:click="commitComposerRecipientInput"
                icon="heroicon-o-plus"
                class="w-full justify-center"
            >
                Adicionar
            </x-filament::button>
        </div>

        @if ($page->composerRecipientSuggestions->isNotEmpty())
            <div class="mt-3 overflow-hidden rounded-[1rem] border border-gray-200/80 bg-white/95 shadow-sm dark:border-white/10 dark:bg-slate-900/95">
                @foreach ($page->composerRecipientSuggestions as $suggestion)
                    <button
                        type="button"
                        wire:key="composer-suggestion-{{ $suggestion['email'] }}"
                        wire:click='selectComposerSuggestion(@json($suggestion["email"]))'
                        class="flex w-full items-center justify-between gap-3 px-3 py-2 text-left transition hover:bg-gray-50 dark:hover:bg-white/5"
                    >
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-medium text-gray-900 dark:text-white">
                                {{ $suggestion['name'] ?: 'Inscrito' }}
                            </span>

                            <span class="block truncate text-xs text-gray-500 dark:text-gray-400">
                                {{ $suggestion['email'] }}
                            </span>
                        </span>

                        <span class="badge badge-ghost badge-sm">Adicionar</span>
                    </button>
                @endforeach
            </div>
        @elseif (filled($page->composerRecipientInput))
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                No desktop, pressione Enter para adicionar. No mobile, toque em "Adicionar" abaixo.
            </div>
        @endif
    </div>
</div>