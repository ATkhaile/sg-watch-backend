<?php

namespace App\Http\Resources\Api\Firebase;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFirebaseNotificationsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('firebase.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'notifications' => $this->resource['data']['notifications'],
                'pagination' => $this->resource['data']['pagination'],
                'notification_unread_count' => $this->resource['data']['notification_unread_count'],
            ],
        ];
    }
}
