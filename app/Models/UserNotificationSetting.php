<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class UserNotificationSetting extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'user_id',
        'new_message_enabled',
        'pinned_user_enabled',
        'thread_reply_enabled',
        'unread_reminder_enabled',
        'reminder_timing',
        'receive_sound_enabled',
        'send_sound_enabled',
    ];

    protected $casts = [
        'new_message_enabled' => 'boolean',
        'pinned_user_enabled' => 'boolean',
        'thread_reply_enabled' => 'boolean',
        'unread_reminder_enabled' => 'boolean',
        'receive_sound_enabled' => 'boolean',
        'send_sound_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
