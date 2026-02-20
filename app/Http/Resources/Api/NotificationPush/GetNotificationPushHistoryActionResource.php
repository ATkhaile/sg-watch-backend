<?php

namespace App\Http\Resources\Api\NotificationPush;

use Illuminate\Http\Resources\Json\JsonResource;

class GetNotificationPushHistoryActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success'     => true,
            'message'     => __('notification_push.list.message'),
            'status_code' => $this->resource['status_code'],
            'data'        => [
                'histories'  => $this->resource['data']['histories'],
                'pagination' => $this->resource['data']['pagination'],
            ],
        ];
    }
}
