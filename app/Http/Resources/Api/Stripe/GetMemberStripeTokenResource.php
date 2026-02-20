<?php

namespace App\Http\Resources\Api\Stripe;

use Illuminate\Http\Resources\Json\JsonResource;

class GetMemberStripeTokenResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'public_key' => $this->resource['data']['public_key'] ?? null,
        ];

        // Include token if available (for testing)
        if (!empty($this->resource['data']['token'])) {
            $data['token'] = is_object($this->resource['data']['token'])
                ? $this->resource['data']['token']->id
                : ($this->resource['data']['token']['id'] ?? null);
        }

        return [
            'success' => true,
            'status_code' => $this->resource['status_code'],
            'data' => $data,
        ];
    }
}
