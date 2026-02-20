<?php

namespace App\Http\Resources\Api\Firebase;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFirebaseUnreadNotificationsActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('firebase.unread.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'notifications' => $this->resource['data']['notifications'],
            ],
        ];
    }
}
