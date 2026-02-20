<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'country_code',
        'input_mode',
        'postal_code',
        'phone',
        'image_url',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jpDetail(): HasOne
    {
        return $this->hasOne(UserAddressJp::class, 'address_id');
    }

    public function vnDetail(): HasOne
    {
        return $this->hasOne(UserAddressVn::class, 'address_id');
    }

    /**
     * Get the country-specific detail (JP or VN)
     */
    public function getDetailAttribute()
    {
        return match ($this->country_code) {
            'JP' => $this->jpDetail,
            'VN' => $this->vnDetail,
            default => null,
        };
    }
}
