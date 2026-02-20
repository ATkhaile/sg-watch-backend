<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAvailableUsersResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('chat.users.list.success'),
            'status_code' => $this->resource['status_code'] ?? 200,
            'data' => [
                'users' => $this->resource['data']['users'] ?? [],
                'pagination' => $this->resource['data']['pagination'] ?? [],
            ],
        ];
    }
}