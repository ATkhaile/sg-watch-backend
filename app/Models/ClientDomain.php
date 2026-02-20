<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientDomain extends Model
{
    use SoftDeletes;

    protected $table = 'client_domains';

    protected $fillable = [
        'client_type',
        'domain',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ssoProviders(): HasMany
    {
        return $this->hasMany(SsoProvider::class, 'client_id');
    }
}
