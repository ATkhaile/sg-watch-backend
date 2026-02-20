<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllPermissionActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('authorization.permission.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'permissions' => $this->resource['data']['permissions'],
                'pagination' => $this->resource['data']['pagination'],
            ],
        ];
    }
}
