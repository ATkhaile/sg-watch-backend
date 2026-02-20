<?php

namespace App\Http\Resources\Api\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class GetNotificationsDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('notifications.detail.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'notification' => $this->resource['notification'],
            ],
        ];
    }
}
