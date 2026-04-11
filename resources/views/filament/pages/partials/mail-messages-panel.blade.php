@php /** @var \App\Filament\Pages\Mailbox $page */ @endphp

<style>
    .mailbox-message-table .fi-ta-table th,
    .mailbox-message-table .fi-ta-table td {
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
    }

    .mailbox-message-table .fi-ta-cell,
    .mailbox-message-table .fi-ta-header-cell {
        min-height: 0;
    }
</style>

<div class="mailbox-message-table overflow-hidden rounded-[1.5rem] border border-gray-200/80 bg-white/90 shadow-sm dark:border-white/10 dark:bg-white/[0.03]">
    {{ $page->table }}
</div>
