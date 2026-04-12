<?php

namespace App\Http\Resources\Api\ShopProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class RestoreProductsByBrandActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message'     => $this->resource['message'],
            'status_code' => $this->resource['success'] ? 200 : 422,
            'data'        => [
                'restored_count' => $this->resource['restored_count'] ?? 0,
            ],
        ];
    }
}
