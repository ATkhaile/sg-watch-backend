<?php

namespace App\Http\Resources\Api\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class GetBannerDetailActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Banner retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'banner' => $this->resource['banner'],
            ],
        ];
    }
}
