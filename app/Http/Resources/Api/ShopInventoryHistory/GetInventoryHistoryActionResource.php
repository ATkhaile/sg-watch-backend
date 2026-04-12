<?php

namespace App\Http\Resources\Api\ShopInventoryHistory;

use Illuminate\Http\Resources\Json\JsonResource;

class GetInventoryHistoryActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message'     => 'Inventory history retrieved successfully.',
            'status_code' => 200,
            'data'        => [
                'records'    => $this->resource['records'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
