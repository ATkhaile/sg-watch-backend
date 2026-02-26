<?php

namespace App\Http\Resources\Api\DiscountCode;

use Illuminate\Http\Resources\Json\JsonResource;

class GetDiscountCodeListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Discount codes retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'discount_codes' => $this->resource['discount_codes'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
