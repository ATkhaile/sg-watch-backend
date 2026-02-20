<?php

namespace App\Http\Resources\Api\Stripe;

use Illuminate\Http\Resources\Json\JsonResource;

class CancelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'data' => $this->resource['data'],
            'redirect' => $this->resource['redirect'],
            'status' => $this->resource['status'],
        ];
    }
}
