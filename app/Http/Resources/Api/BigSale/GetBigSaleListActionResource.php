<?php

namespace App\Http\Resources\Api\BigSale;

use Illuminate\Http\Resources\Json\JsonResource;

class GetBigSaleListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Big sales retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'big_sales' => $this->resource['big_sales'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
