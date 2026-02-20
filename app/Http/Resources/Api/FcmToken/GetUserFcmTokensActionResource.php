<?php

namespace App\Http\Resources\Api\FcmToken;

use Illuminate\Http\Resources\Json\JsonResource;

class GetUserFcmTokensActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success'     => true,
            'message'     => __('fcm_token.list.message'),
            'status_code' => $this->resource['status_code'],
            'data'        => [
                'fcm_tokens' => $this->resource['data']['fcm_tokens'],
            ],
        ];
    }
}
