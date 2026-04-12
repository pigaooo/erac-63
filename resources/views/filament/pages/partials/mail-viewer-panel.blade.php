@php /** @var \App\Filament\Pages\Mailbox $page */ @endphp

@if ($page->showComposer)
    <form wire:submit="sendComposer" class="mailbox-reader space-y-5">
        <div class="flex items-center justify-between gap-3">
            <x-filament::button type="button" color="gray" wire:click="backToMessageList" icon="heroicon-o-arrow-left">
                Voltar para mensagens
            </x-filament::button>

            <span class="text-xs text-gray-500 dark:text-gray-400">
                Composer aberto
            </span>
        </div>

        <div class="rounded-[1.5rem] border border-gray-200/80 bg-white/85 p-4 shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            @include('filament.pages.partials.mail-composer-to', ['page' => $page])

            <div class="mt-4">
                {{ $page->composerForm }}
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 rounded-[1.25rem] border border-gray-200/80 bg-gray-50/80 px-4 py-3 dark:border-white/10 dark:bg-white/[0.04]">
            <x-filament::button type="button" color="gray" wire:click="closeComposer">
                Fechar
            </x-filament::button>

            <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                Enviar
            </x-filament::button>
        </div>
    </form>
@elseif ($page->selectedMessage)
    <div class="mailbox-reader space-y-4">
        <div class="mailbox-actionbar flex flex-wrap items-center justify-between gap-3 rounded-[1.25rem] border border-gray-200/80 bg-white/85 px-4 py-3 shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            <x-filament::button size="sm" color="gray" wire:click="backToMessageList" icon="heroicon-o-arrow-left">
                Voltar
            </x-filament::button>

            <div class="flex flex-wrap items-center gap-2">
                <div class="join mailbox-actionbar-group">
                    <x-filament::button size="sm" wire:click="replyToSelectedMessage" icon="heroicon-o-arrow-uturn-left" class="join-item">
                        Responder
                    </x-filament::button>

                    <x-filament::button size="sm" color="gray" wire:click="forwardSelectedMessage" icon="heroicon-o-arrow-top-right-on-square" class="join-item">
                        Encaminhar
                    </x-filament::button>

                    @if ($page->selectedMessage->is_seen)
                        <x-filament::button size="sm" color="gray" wire:click="markSelectedMessageSeen(false)" icon="heroicon-o-envelope" class="join-item">
                            Nao lido
                        </x-filament::button>
                    @else
                        <x-filament::button size="sm" color="gray" wire:click="markSelectedMessageSeen(true)" icon="heroicon-o-envelope-open" class="join-item">
                            Lido
                        </x-filament::button>
                    @endif
                </div>

                <details class="dropdown dropdown-end">
                    <summary class="btn btn-sm btn-ghost mailbox-actionbar-menu">
                        <x-filament::icon icon="heroicon-o-folder" class="h-4 w-4" />
                        <span>Mover</span>
                        <x-filament::icon icon="heroicon-o-chevron-down" class="h-4 w-4 opacity-60" />
                    </summary>

                    <div class="dropdown-content z-20 mt-2 w-72 rounded-2xl border border-gray-200/80 bg-white/95 p-3 shadow-2xl dark:border-white/10 dark:bg-slate-900/95">
                        <div class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">
                            Mover para
                        </div>

                        <div class="space-y-2">
                            <label class="input input-bordered mailbox-account-input w-full bg-white/80 dark:bg-white/[0.04]">
                                <x-filament::icon icon="heroicon-o-folder" class="h-4 w-4 text-gray-400" />

                                <select
                                    wire:model="moveDestinationId"
                                    class="w-full bg-transparent text-sm focus:outline-none"
                                >
                                    <option value="">Selecione a pasta</option>
                                    @foreach ($page->moveDestinations as $folder)
                                        <option value="{{ $folder->id }}">{{ $folder->display_name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <x-filament::button wire:click="moveSelectedMessage" color="gray" icon="heroicon-o-arrow-right-circle" class="w-full justify-center">
                                Confirmar movimento
                            </x-filament::button>
                        </div>
                    </div>
                </details>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-gray-200/80 bg-white/90 p-5 shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            <div class="mb-4 border-b border-gray-200 pb-4 dark:border-white/10">
                <div class="mb-3 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-950 dark:text-white">
                            {{ $page->selectedMessage->subject ?: '(sem assunto)' }}
                        </h2>

                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="badge badge-ghost">Conta {{ $page->selectedMessage->account?->email_address }}</span>
                            <span class="badge badge-ghost">{{ $page->selectedMessage->received_at?->format('d/m/Y H:i') ?: '-' }}</span>
                            @if ($page->selectedMessage->has_attachments)
                                <span class="badge badge-outline badge-info">Com anexos</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mailbox-message-meta rounded-[1.25rem] border border-gray-200/80 bg-gray-50/80 p-4 dark:border-white/10 dark:bg-white/[0.03]">
                    <div class="grid gap-3 lg:grid-cols-[minmax(0,1.35fr)_minmax(18rem,0.8fr)]">
                        <div class="mailbox-meta-group mailbox-meta-group--people">
                            <div class="mailbox-meta-inline">
                                <div class="mailbox-meta-chip">
                                    <span class="mailbox-meta-chip-label">De</span>
                                    <span class="mailbox-meta-chip-value">{{ $page->presentAddresses($page->selectedMessage->from_addresses ?? []) }}</span>
                                </div>

                                <div class="mailbox-meta-chip">
                                    <span class="mailbox-meta-chip-label">Para</span>
                                    <span class="mailbox-meta-chip-value">{{ $page->presentAddresses($page->selectedMessage->to_addresses ?? []) }}</span>
                                </div>

                                @if (($page->selectedMessage->cc_addresses ?? []) !== [])
                                    <div class="mailbox-meta-chip">
                                        <span class="mailbox-meta-chip-label">Cc</span>
                                        <span class="mailbox-meta-chip-value">{{ $page->presentAddresses($page->selectedMessage->cc_addresses ?? []) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mailbox-meta-group mailbox-meta-group--state">
                            <div class="mailbox-meta-inline">
                                <div class="mailbox-meta-chip is-compact">
                                    <span class="mailbox-meta-chip-label">Status</span>
                                    <span class="flex flex-wrap gap-2">
                                        <span @class([
                                            'badge',
                                            $page->selectedMessage->is_seen ? 'badge-ghost' : 'badge-warning badge-outline',
                                        ])>
                                            {{ $page->selectedMessage->is_seen ? 'Lida' : 'Nao lida' }}
                                        </span>

                                        <span class="badge badge-ghost">
                                            {{ $page->selectedMessage->direction === 'outbound' ? 'Saida' : 'Entrada' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="mailbox-meta-chip is-compact">
                                    <span class="mailbox-meta-chip-label">Detalhes</span>
                                    <span class="mailbox-meta-chip-value">
                                        {{ $page->selectedMessage->received_at?->format('d/m/Y H:i') ?: '-' }}
                                        ·
                                        {{ $page->selectedMessage->folder?->display_name ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($page->selectedMessage->attachments->isNotEmpty())
                        <div class="mt-4 border-t border-gray-200 pt-4 dark:border-white/10">
                            <div class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">
                                Anexos
                            </div>

                            <div class="grid gap-2 md:grid-cols-2">
                                @foreach ($page->selectedMessage->attachments as $attachment)
                                    @php
                                        $thumb = $page->attachmentThumbnailDataUrl($attachment->path, $attachment->content_type, $attachment->size);
                                    @endphp
                                    <button
                                        type="button"
                                        wire:click="downloadAttachment({{ $attachment->id }})"
                                        class="mailbox-attachment-card w-full text-left transition hover:-translate-y-0.5"
                                    >
                                        <div class="flex gap-3">
                                            <div class="mailbox-attachment-preview">
                                                @if ($thumb)
                                                    <img
                                                        src="{{ $thumb }}"
                                                        alt="{{ $attachment->filename ?: 'anexo' }}"
                                                        class="h-full w-full object-cover"
                                                    >
                                                @else
                                                    <div class="mailbox-attachment-preview-fallback">
                                                        <x-filament::icon
                                                            :icon="$page->attachmentIcon($attachment->content_type, $attachment->filename)"
                                                            class="h-6 w-6"
                                                        />
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="truncate font-medium text-gray-900 dark:text-white">
                                                    {{ $attachment->filename ?: 'anexo' }}
                                                </div>

                                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                    <span class="badge badge-ghost badge-sm">
                                                        {{ $page->attachmentTypeLabel($attachment->content_type, $attachment->filename) }}
                                                    </span>

                                                    <span>{{ $page->attachmentSizeLabel($attachment->size) }}</span>
                                                </div>

                                                @if ($attachment->is_inline)
                                                    <div class="mt-2 text-[11px] font-medium uppercase tracking-[0.14em] text-gray-400 dark:text-gray-500">
                                                        Inline
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-primary-500">
                                            Clique para baixar
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="prose prose-sm max-w-none dark:prose-invert mailbox-message-body">
                {!! $page->renderedSelectedMessageBody() !!}
            </div>
        </div>

    </div>
@else
    <div class="mailbox-empty-reader flex min-h-[720px] items-center justify-center rounded-[1.75rem] border border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-white px-8 text-center dark:border-white/10 dark:from-white/[0.03] dark:to-white/[0.02]">
        <div class="space-y-3">
            <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-500/10 text-primary-500">
                <x-filament::icon icon="heroicon-o-envelope-open" class="h-7 w-7" />
            </div>

            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                Selecione uma mensagem
            </div>

            <p class="max-w-sm text-sm text-gray-500 dark:text-gray-400">
                Abra um email na lista central para ler, responder, encaminhar ou mover entre as pastas da conta.
            </p>
        </div>
    </div>
@endif
