<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllChatHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('chat.list.message'),
            'status_code' => $this->resource['status_code'] ?? 200,
            'data' => [
                'messages' => $this->resource['data']['messages'] ?? [],
                'unread_count' => $this->resource['data']['unread_count'] ?? 0,
                'pagination' => $this->resource['data']['pagination'] ?? [],
            ],
        ];
    }
}
