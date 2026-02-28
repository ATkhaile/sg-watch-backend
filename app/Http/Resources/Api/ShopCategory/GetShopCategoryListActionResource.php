<?php

namespace App\Http\Resources\Api\ShopCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class GetShopCategoryListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Shop categories retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'categories' => $this->resource['categories'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
