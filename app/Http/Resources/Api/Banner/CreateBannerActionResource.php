<?php

namespace App\Http\Resources\Api\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateBannerActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'banner' => $this->resource['banner'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
