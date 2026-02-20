<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SsoProviderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserIdentity extends Model
{
    use SoftDeletes;

    protected $table = 'user_identities';

    protected $fillable = [
        'user_id',
        'provider_type',
        'provider_user_id',
        'linked_at',
    ];

    protected $casts = [
        'provider_type' => SsoProviderType::class,
        'linked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
