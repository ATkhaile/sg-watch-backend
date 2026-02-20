<?php

namespace App\Http\Resources\Api\StripeAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStripeTransactionsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('stripe_account.transactions.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'transactions' => $this->resource['transactions'],
                'total' => $this->resource['total'],
                'has_more' => $this->resource['has_more'],
                'next_cursor' => $this->resource['next_cursor'],
            ],
        ];
    }
}
