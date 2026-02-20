<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class GetPermissionDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return[
            'success' => true,
            'message' => __('authorization.permission.find.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'permission' => $this->resource['permission'],
            ],
        ];
    }
}
