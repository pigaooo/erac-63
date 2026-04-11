<?php

namespace App\Support\Mail\Contracts;

use App\Models\MailAccount;

interface ImapClient
{
    public function testConnection(MailAccount $account): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listFolders(MailAccount $account): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchFolderMessages(MailAccount $account, string $remoteFolder): array;

    /**
     * @return array<string, mixed>
     */
    public function fetchMessage(MailAccount $account, string $remoteFolder, int $uid): array;

    public function setMessageSeen(MailAccount $account, string $remoteFolder, int $uid, bool $seen): void;

    public function moveMessage(MailAccount $account, string $fromRemoteFolder, int $uid, string $toRemoteFolder): void;
}
