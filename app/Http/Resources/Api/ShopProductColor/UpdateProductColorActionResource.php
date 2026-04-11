<?php

namespace App\Http\Resources\Api\ShopProductColor;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateProductColorActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 200,
            'data' => [
                'color' => $this->resource['color'],
            ],
        ];
    }
}
