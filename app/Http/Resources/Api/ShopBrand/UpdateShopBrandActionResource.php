<?php

namespace App\Http\Resources\Api\ShopBrand;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateShopBrandActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $this->resource['message'],
            'status_code' => $success ? 200 : 404,
            'data' => $success ? [
                'brand' => $this->resource['brand'],
            ] : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 200 : 404;
        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
