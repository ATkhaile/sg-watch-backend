<?php

namespace App\Http\Resources\Api\ShopBrand;

use Illuminate\Http\Resources\Json\JsonResource;

class GetShopBrandListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Shop brands retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'brands' => $this->resource['brands'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
