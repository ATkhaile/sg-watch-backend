<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class EmailVerificationCode extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'email',
        'code',
        'type',
        'expires_at',
        'is_used',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
        'attempts' => 'integer',
    ];
}
