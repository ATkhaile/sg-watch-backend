<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllRoleActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('authorization.role.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'roles' => $this->resource['data']['roles'],
                'pagination' => $this->resource['data']['pagination'],
            ],
        ];
    }
}
