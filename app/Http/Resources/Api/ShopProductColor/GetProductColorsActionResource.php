<?php

namespace App\Http\Resources\Api\ShopProductColor;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProductColorsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status_code' => 200,
            'data' => [
                'colors' => $this->resource['colors'],
            ],
        ];
    }
}
