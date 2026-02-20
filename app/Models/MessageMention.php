<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageMention extends Model
{
    protected $table = 'message_mentions';

    protected $fillable = [
        'message_id',
        'mentioned_user_id',
    ];

    /**
     * Get the message that owns the mention.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessageModel::class, 'message_id');
    }

    /**
     * Get the mentioned user.
     */
    public function mentionedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }
}
