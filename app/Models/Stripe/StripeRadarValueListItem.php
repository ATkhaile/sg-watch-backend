<?php

namespace App\Models\Stripe;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeRadarValueListItem extends BaseStripeModel
{
    protected $table = 'stripe_radar_value_list_items';

    protected $fillable = [
        'stripe_account_id',
        'stripe_id',
        'value_list_id',
        'value',
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

    public function valueList(): BelongsTo
    {
        return $this->belongsTo(StripeRadarValueList::class, 'value_list_id', 'stripe_id')
            ->where('stripe_account_id', $this->stripe_account_id);
    }
}
