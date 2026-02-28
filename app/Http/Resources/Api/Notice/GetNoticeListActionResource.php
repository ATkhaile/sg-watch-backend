<?php

namespace App\Http\Resources\Api\Notice;

use Illuminate\Http\Resources\Json\JsonResource;

class GetNoticeListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Notices retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'notices' => $this->resource['notices'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
