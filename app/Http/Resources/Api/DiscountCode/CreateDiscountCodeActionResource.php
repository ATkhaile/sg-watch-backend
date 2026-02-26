<?php

namespace App\Http\Resources\Api\DiscountCode;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateDiscountCodeActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'discount_code' => $this->resource['discount_code'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
