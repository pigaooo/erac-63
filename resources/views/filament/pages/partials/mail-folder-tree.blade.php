@php
    /** @var array{folder: \App\Models\MailFolder|null, label: string, children: array} $node */
    $folder = $node['folder'];
    $padding = ($level * 16) + 6;
@endphp

<div class="space-y-1">
    @if ($folder)
        <button
            type="button"
            wire:click.prevent.stop="openFolder({{ $folder->id }})"
            wire:key="mail-folder-{{ $folder->id }}"
            @class([
                'mailbox-folder-item flex w-full items-center justify-between rounded-2xl border px-3 py-2.5 text-left text-sm transition',
                'is-active bg-primary-600 text-white shadow-lg ring-1 ring-primary-400/40' => $selectedFolderId === $folder->id,
                'border-transparent text-gray-700 hover:border-gray-200 hover:bg-white dark:text-gray-200 dark:hover:border-white/10 dark:hover:bg-white/[0.05]' => $selectedFolderId !== $folder->id,
            ])
            style="padding-left: {{ $padding }}px"
        >
            <span class="flex min-w-0 items-center gap-3">
                <span class="mailbox-folder-icon inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/70 text-gray-500 dark:bg-white/[0.05]">
                    <x-filament::icon
                        :icon="$page->folderIcon($folder)"
                        class="h-4 w-4"
                    />
                </span>

                <span class="min-w-0">
                    <span class="block truncate font-medium">{{ $node['label'] }}</span>

                    <span class="block text-[11px] text-gray-500 dark:text-gray-400">
                        {{ $folder->messages_count }} mensagens
                    </span>
                </span>
            </span>

            <span @class([
                'badge badge-sm shrink-0',
                'badge-neutral border-white/10 bg-white/15 text-white' => $selectedFolderId === $folder->id && ($folder->unread_messages_count > 0),
                'badge-ghost text-gray-500 dark:text-gray-300' => $folder->unread_messages_count === 0,
                'badge-warning badge-outline' => $selectedFolderId !== $folder->id && ($folder->unread_messages_count > 0),
            ])>
                {{ $folder->unread_messages_count > 0 ? $folder->unread_messages_count : $folder->messages_count }}
            </span>
        </button>
    @endif

    @if ($node['children'] !== [])
        <div class="space-y-1">
            @foreach ($node['children'] as $child)
                @include('filament.pages.partials.mail-folder-tree', ['node' => $child, 'level' => $level + 1, 'selectedFolderId' => $selectedFolderId])
            @endforeach
        </div>
    @endif
</div>
