<?php

namespace App\Http\Resources\Api\ShopOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class GetOrderListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Orders retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'orders' => $this->resource['orders'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
