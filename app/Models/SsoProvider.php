<?php

namespace App\Models;

use App\Enums\SsoProviderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SsoProvider extends Model
{
    use SoftDeletes;

    protected $table = 'sso_providers';

    protected $fillable = [
        'provider_type',
        'client_type',
        'client_key',
        'client_secret',
        'scopes',
        'status',
        'tenant_id', 
        'apple_team_id',
        'apple_private_key',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'provider_type' => SsoProviderType::class,
    ];
}
