<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_account_id',
        'remote_name',
        'display_name',
        'delimiter',
        'attributes',
        'special_use',
        'uid_validity',
        'remote_hash',
        'is_active',
        'is_selectable',
        'last_synced_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
            'is_active' => 'boolean',
            'is_selectable' => 'boolean',
            'last_synced_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(MailAccount::class, 'mail_account_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MailMessage::class);
    }
}
