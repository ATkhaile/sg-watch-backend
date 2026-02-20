<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStripePaymentLinksActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.payment_links.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'stripe_payment_links' => $this->resource['stripe_payment_links'],
            ],
        ];
    }
}
