<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStripeAccountDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.find.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'stripe_account' => $this->resource['stripe_account'],
            ],
        ];
    }
}
