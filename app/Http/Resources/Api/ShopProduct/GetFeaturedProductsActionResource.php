<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFeaturedProductsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Featured products retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'products' => $this->resource['products'],
            ],
        ];
    }
}
