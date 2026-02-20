<?php

namespace App\Http\Resources\Api\Stripe;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status' => $this->resource['status'],
            'message' => $this->resource['message'],
        ];
    }
}
