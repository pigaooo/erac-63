<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email_address',
        'from_name',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'imap_validate_cert',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'smtp_ehlo_domain',
        'inbox_folder_name',
        'sent_folder_name',
        'drafts_folder_name',
        'spam_folder_name',
        'trash_folder_name',
        'is_active',
        'sync_interval_minutes',
        'last_synced_at',
        'last_error_at',
        'last_error_message',
    ];

    protected function casts(): array
    {
        return [
            'imap_password' => 'encrypted',
            'smtp_password' => 'encrypted',
            'imap_port' => 'integer',
            'smtp_port' => 'integer',
            'imap_validate_cert' => 'boolean',
            'is_active' => 'boolean',
            'sync_interval_minutes' => 'integer',
            'last_synced_at' => 'datetime',
            'last_error_at' => 'datetime',
        ];
    }

    public function folders(): HasMany
    {
        return $this->hasMany(MailFolder::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MailMessage::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(MailEvent::class);
    }
}
