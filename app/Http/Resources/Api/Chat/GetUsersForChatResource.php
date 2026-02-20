<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class GetUsersForChatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status_code' => $this->resource['status_code'] ?? 200,
            'message' => __('users.list.message'),
            'data' => [
                'users' => $this->resource['data']['users'] ?? [],
                'pagination' => $this->resource['data']['pagination'] ?? [],
            ],
        ];
    }
}