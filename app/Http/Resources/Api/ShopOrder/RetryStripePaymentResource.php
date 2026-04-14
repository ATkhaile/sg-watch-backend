<?php

namespace App\Http\Resources\Api\ShopOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class RetryStripePaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $this->resource['message'],
            'status_code' => $success ? 200 : 422,
            'data' => $success ? array_filter([
                'order' => $this->resource['order'],
                'stripe_client_secret' => $this->resource['stripe_client_secret'] ?? null,
                'stripe_public_key' => $this->resource['stripe_public_key'] ?? null,
                'already_paid' => $this->resource['already_paid'] ?? null,
            ]) : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 200 : 422;

        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
