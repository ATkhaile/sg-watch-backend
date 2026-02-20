<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class GetChatPartnerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('chat.partner.get.success'),
            'status_code' => $this->resource['status_code'] ?? 200,
            'data' => [
                'partner' => $this->resource['data']['partner'] ?? null,
            ],
        ];
    }
}
