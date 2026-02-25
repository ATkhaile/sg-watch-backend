<?php

namespace App\Http\Resources\Api\AdminUser;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAdminUserDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'User retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'user' => $this->resource['user'],
            ],
        ];
    }
}
