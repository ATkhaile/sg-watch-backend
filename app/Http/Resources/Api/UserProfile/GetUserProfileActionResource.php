<?php

namespace App\Http\Resources\Api\UserProfile;

use Illuminate\Http\Resources\Json\JsonResource;

class GetUserProfileActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('user_profile.get.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'profile' => $this->resource['data']['profile'],
            ],
        ];
    }
}
