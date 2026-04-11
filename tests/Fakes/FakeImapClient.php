<?php

namespace Tests\Fakes;

use App\Models\MailAccount;
use App\Support\Mail\Contracts\ImapClient;

class FakeImapClient implements ImapClient
{
    /** @var array<int, array<string, mixed>> */
    public array $folders = [];

    /** @var array<string, array<int, array<string, mixed>>> */
    public array $messagesByFolder = [];

    /** @var array<string, array<string, mixed>> */
    public array $messagePayloads = [];

    public array $seenChanges = [];

    public array $movedMessages = [];

    public function testConnection(MailAccount $account): void
    {
    }

    public function listFolders(MailAccount $account): array
    {
        return $this->folders;
    }

    public function fetchFolderMessages(MailAccount $account, string $remoteFolder): array
    {
        return $this->messagesByFolder[$remoteFolder] ?? [];
    }

    public function fetchMessage(MailAccount $account, string $remoteFolder, int $uid): array
    {
        return $this->messagePayloads[$remoteFolder . ':' . $uid] ?? [
            'text_body' => null,
            'html_body' => null,
            'attachments' => [],
            'has_attachments' => false,
        ];
    }

    public function setMessageSeen(MailAccount $account, string $remoteFolder, int $uid, bool $seen): void
    {
        $this->seenChanges[] = compact('remoteFolder', 'uid', 'seen');
    }

    public function moveMessage(MailAccount $account, string $fromRemoteFolder, int $uid, string $toRemoteFolder): void
    {
        $this->movedMessages[] = compact('fromRemoteFolder', 'uid', 'toRemoteFolder');
    }
}
