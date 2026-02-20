<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StripeRadarValueList extends BaseStripeModel
{
    protected $table = 'stripe_radar_value_lists';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'name',
        'alias',
        'item_type',
        'livemode',
        'stripe_created',
        'remarks',
        'creator',
        'updater',
    ];

    protected $casts = [
        'stripe_created' => 'datetime',
        'livemode' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(StripeRadarValueListItem::class, 'value_list_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
