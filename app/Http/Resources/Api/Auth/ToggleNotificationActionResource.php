<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ToggleNotificationActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = $this->resource['user_info'];
        return [
            'message' => 'Notification settings updated successfully',
            'status_code' => 200,
            'data' => [
                'user' => $user->jsonSerialize()
            ]
        ];
    }
}
