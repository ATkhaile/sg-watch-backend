<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStripeProductsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.products.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'stripe_products' => $this->resource['stripe_products'],
            ],
        ];
    }
}
