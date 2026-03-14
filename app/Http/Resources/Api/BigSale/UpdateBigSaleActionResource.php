<?php

namespace App\Http\Resources\Api\BigSale;

use Illuminate\Http\Resources\Json\JsonResource;

class UpdateBigSaleActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $this->resource['message'],
            'status_code' => $success ? 200 : 404,
            'data' => $success ? [
                'big_sale' => $this->resource['big_sale'],
            ] : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 200 : 404;
        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
