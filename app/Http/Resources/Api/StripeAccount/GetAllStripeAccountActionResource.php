<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllStripeAccountActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'stripe_accounts' => $this->resource['data']['stripe_account'],
                'pagination' => $this->resource['data']['pagination'],
            ],
        ];
    }
}
