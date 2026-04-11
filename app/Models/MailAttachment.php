<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_message_id',
        'part_number',
        'filename',
        'content_type',
        'size',
        'content_id',
        'path',
        'is_inline',
        'is_downloaded',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'is_inline' => 'boolean',
            'is_downloaded' => 'boolean',
        ];
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(MailMessage::class, 'mail_message_id');
    }
}
