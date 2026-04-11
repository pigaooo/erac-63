@php /** @var \App\Filament\Pages\Mailbox $page */ @endphp

<div class="mailbox-folders-wrap space-y-3">
    <x-filament::button wire:click="composeNew" icon="heroicon-o-pencil-square" class="w-full justify-center">
        Escrever
    </x-filament::button>

    @if ($page->folderTree !== [])
        <div class="menu rounded-box mailbox-folder-menu w-full p-2">
            @foreach ($page->folderTree as $node)
                @include('filament.pages.partials.mail-folder-tree', ['node' => $node, 'level' => 0, 'selectedFolderId' => $page->selectedFolderId])
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-gray-300 px-4 py-6 text-sm text-gray-500 dark:border-white/10 dark:text-gray-400">
            Nenhuma pasta sincronizada para esta conta.
        </div>
    @endif
</div>
