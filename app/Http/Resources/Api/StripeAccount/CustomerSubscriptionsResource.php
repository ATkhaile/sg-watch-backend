<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerSubscriptionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.customers.subscriptions.message'),
            'status_code' => $this->resource->getStatus(),
            'data' => [
                'customers' => $this->resource->getCustomers(),
                'pagination' => $this->resource->getPagination()
            ]
        ];
    }
}
