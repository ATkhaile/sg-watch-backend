<?php

namespace App\Http\Resources\Api\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class GetBannerListActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'Banners retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'banners' => $this->resource['banners'],
                'pagination' => $this->resource['pagination'],
            ],
        ];
    }
}
