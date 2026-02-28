<?php

namespace App\Http\Resources\Api\ShopCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class GetShopCategoryDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Shop category retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'category' => $this->resource['category'],
            ],
        ];
    }
}
