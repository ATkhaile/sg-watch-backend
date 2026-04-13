<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSystemNoticeRead extends Model
{
    protected $fillable = [
        'user_id',
        'notice_id',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notice(): BelongsTo
    {
        return $this->belongsTo(Notice::class);
    }
}
