<?php

namespace App\Http\Resources\Api\ShopBrand;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateShopBrandActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'brand' => $this->resource['brand'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
