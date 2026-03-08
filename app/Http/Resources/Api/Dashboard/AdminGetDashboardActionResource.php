<?php

namespace App\Http\Resources\Api\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminGetDashboardActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Dashboard data retrieved successfully.',
            'status_code' => 200,
            'data' => $this->resource,
        ];
    }
}
