<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminGetProductListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Products retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'products' => $this->resource['products'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
