<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_account_id',
        'mail_folder_id',
        'uid',
        'remote_message_id',
        'subject',
        'from_addresses',
        'to_addresses',
        'cc_addresses',
        'bcc_addresses',
        'reply_to_addresses',
        'headers',
        'snippet',
        'text_body',
        'html_body',
        'received_at',
        'sent_at',
        'has_attachments',
        'is_seen',
        'is_answered',
        'is_flagged',
        'is_draft',
        'is_deleted',
        'direction',
        'sync_status',
        'last_remote_update_at',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'uid' => 'integer',
            'from_addresses' => 'array',
            'to_addresses' => 'array',
            'cc_addresses' => 'array',
            'bcc_addresses' => 'array',
            'reply_to_addresses' => 'array',
            'headers' => 'array',
            'has_attachments' => 'boolean',
            'is_seen' => 'boolean',
            'is_answered' => 'boolean',
            'is_flagged' => 'boolean',
            'is_draft' => 'boolean',
            'is_deleted' => 'boolean',
            'received_at' => 'datetime',
            'sent_at' => 'datetime',
            'last_remote_update_at' => 'datetime',
            'synced_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(MailAccount::class, 'mail_account_id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(MailFolder::class, 'mail_folder_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MailAttachment::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(MailEvent::class);
    }
}
