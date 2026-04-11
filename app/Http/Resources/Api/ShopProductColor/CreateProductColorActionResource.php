<?php

namespace App\Http\Resources\Api\ShopProductColor;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateProductColorActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'color' => $this->resource['color'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
