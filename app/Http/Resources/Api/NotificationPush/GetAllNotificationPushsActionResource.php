<?php 

namespace App\Http\Resources\Api\NotificationPush;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllNotificationPushsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success'     => true,
            'message'     => __('notification_push.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'notification_pushs' => $this->resource['data']['notification_pushs'],
                'pagination'         => $this->resource['data']['pagination'],
            ],
        ];
    }
}
