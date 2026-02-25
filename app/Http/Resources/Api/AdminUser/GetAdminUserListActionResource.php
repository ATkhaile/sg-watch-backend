<?php

namespace App\Http\Resources\Api\AdminUser;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAdminUserListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Users retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'users' => $this->resource['users'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
