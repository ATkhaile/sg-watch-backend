<?php

namespace App\Http\Resources\Api\ShopOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class GetOrderDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Order retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'order' => $this->resource['order'],
            ],
        ];
    }
}
