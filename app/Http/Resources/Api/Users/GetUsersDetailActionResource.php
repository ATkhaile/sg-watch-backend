<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class GetUsersDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $statusCode = (int) $this->resource['status_code'];
        return [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'message' => __('users.find.message'),
            'status_code' => $statusCode,
            'data' => [
                'users' => $this->resource['users'],
            ],
        ];
    }
}
