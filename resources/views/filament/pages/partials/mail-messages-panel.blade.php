@php /** @var \App\Filament\Pages\Mailbox $page */ @endphp

<style>
    .mailbox-message-table .fi-ta-table tbody td {
        padding-top: 0.3rem;
        padding-bottom: 0.3rem;
    }

    .mailbox-message-table .fi-ta-cell,
    .mailbox-message-table .fi-ta-header-cell {
        min-height: 0;
    }

    .mailbox-message-table .fi-ta-selection-cell,
    .mailbox-message-table .fi-ta-selection-cell .fi-input-wrp,
    .mailbox-message-table .fi-ta-selection-cell .fi-checkbox-input {
        vertical-align: middle;
    }

    .mailbox-message-table .fi-ta-table tbody .fi-ta-selection-cell {
        padding-top: 0.3rem;
        padding-bottom: 0.3rem;
    }

    .mailbox-message-table .fi-ta-selection-cell .fi-input-wrp {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100%;
    }
</style>

<div class="mailbox-message-table overflow-hidden rounded-[1.5rem] border border-gray-200/80 bg-white/90 shadow-sm dark:border-white/10 dark:bg-white/[0.03]">
    {{ $page->table }}
</div>
