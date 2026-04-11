<?php

namespace App\Http\Resources\Api\Firebase;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFirebaseNotificationDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('firebase.detail.message'),
            'status_code' => $this->resource['status_code'],
            'data' => $this->resource['data'],
        ];
    }
}
