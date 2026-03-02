<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateFeaturedProductsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 200,
            'data' => [
                'products' => $this->resource['products'],
            ],
        ];
    }
}
