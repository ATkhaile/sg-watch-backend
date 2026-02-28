<?php

namespace App\Http\Resources\Api\ShopCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateShopCategoryActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'category' => $this->resource['category'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
