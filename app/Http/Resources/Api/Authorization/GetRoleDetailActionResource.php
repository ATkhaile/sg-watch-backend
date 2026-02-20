<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class GetRoleDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return[
            'success' => true,
            'message' => __('authorization.role.find.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'role' => $this->resource['role'],
            ],
        ];
    }
}
