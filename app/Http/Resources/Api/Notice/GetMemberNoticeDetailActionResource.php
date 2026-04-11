<?php

namespace App\Http\Resources\Api\Notice;

use Illuminate\Http\Resources\Json\JsonResource;

class GetMemberNoticeDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Notice retrieved successfully.',
            'status_code' => 200,
            'data' => $this->resource,
        ];
    }
}
