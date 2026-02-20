<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllUsersActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $statusCode = (int) $this->resource['status_code'];
        return [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'message' => __('users.list.message'),
            'status_code' => $statusCode,
            'data' => [
                'users' => $this->resource['data']['users'],
                'pagination' => $this->resource['data']['pagination'],
            ],
        ];
    }
}
