<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ChatMessage extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'chat_messages';

    protected $fillable = [
        'user_id',
        'receiver_id',
        'reply_to_message_id',
        'message',
        'file_url',
        'file_name',
        'file_type',
        'file_size',
        'message_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function replyToMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_message_id');
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(MessageMention::class, 'message_id');
    }

}
