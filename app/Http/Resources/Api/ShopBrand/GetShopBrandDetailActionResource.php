<?php

namespace App\Http\Resources\Api\ShopBrand;

use Illuminate\Http\Resources\Json\JsonResource;

class GetShopBrandDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Shop brand retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'brand' => $this->resource['brand'],
            ],
        ];
    }
}
