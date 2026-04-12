<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class DeleteProductsByBrandActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message'     => $this->resource['message'],
            'status_code' => $this->resource['success'] ? 200 : 422,
            'data'        => [
                'deleted_count' => $this->resource['deleted_count'] ?? 0,
            ],
        ];
    }
}
