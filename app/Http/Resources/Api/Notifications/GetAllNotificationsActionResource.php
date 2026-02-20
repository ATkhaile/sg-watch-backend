<?php

namespace App\Http\Resources\Api\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllNotificationsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('notifications.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'notifications' => $this->resource['data']['notifications'],
                'pagination' => $this->resource['data']['pagination'],
            ],
        ];
    }
}
