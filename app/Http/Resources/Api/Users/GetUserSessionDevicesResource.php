<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class GetUserSessionDevicesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('users.list.message'),
            'status_code' => $this->resource->statusCode,
            'data' => [
                'sessions' => $this->resource->sessions,
                'pagination' => $this->resource->pagination,
            ],
        ];
    }
}
