<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProductDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Product retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'product' => $this->resource['product'],
            ],
        ];
    }
}
