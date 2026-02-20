<?php

namespace App\Http\Resources\Api\Stripe;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestCancelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'redirect' => $this->resource['redirect'],
            'status' => $this->resource['status'],
        ];
    }
}
