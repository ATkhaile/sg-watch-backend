<?php

namespace App\Http\Resources\Api\DiscountCode;

use Illuminate\Http\Resources\Json\JsonResource;

class GetDiscountCodeDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Discount code retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'discount_code' => $this->resource['discount_code'],
            ],
        ];
    }
}
