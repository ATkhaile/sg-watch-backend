<?php

namespace App\Http\Resources\Api\BigSale;

use Illuminate\Http\Resources\Json\JsonResource;

class GetBigSaleDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Big sale retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'big_sale' => $this->resource['big_sale'],
            ],
        ];
    }
}
