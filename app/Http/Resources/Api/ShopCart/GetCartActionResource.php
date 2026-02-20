<?php

namespace App\Http\Resources\Api\ShopCart;

use Illuminate\Http\Resources\Json\JsonResource;

class GetCartActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Cart retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'cart' => $this->resource['cart'],
            ],
        ];
    }
}
