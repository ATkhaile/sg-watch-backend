<?php 

namespace App\Http\Resources\Api\NotificationPush;

use Illuminate\Http\Resources\Json\JsonResource;

class GetNotificationPushDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success'     => true,
            'message'     => __('notification_push.find.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'notification_push' => $this->resource['notification_push'],
            ],
        ];
    }
}
