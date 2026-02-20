<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStripePricesActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.prices.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'stripe_prices' => $this->resource['stripe_prices'],
            ],
        ];
    }
}
