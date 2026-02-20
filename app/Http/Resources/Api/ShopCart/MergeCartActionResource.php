<?php

namespace App\Http\Resources\Api\ShopCart;

use Illuminate\Http\Resources\Json\JsonResource;

class MergeCartActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Cart merged successfully.',
            'status_code' => 200,
            'data' => [
                'cart' => $this->resource['cart'],
            ],
        ];
    }
}
