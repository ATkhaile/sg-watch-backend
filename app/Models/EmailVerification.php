<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class EmailVerification extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'affiliate_id',
        'email',
        'name',
        'inviter_id',
        'password',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
