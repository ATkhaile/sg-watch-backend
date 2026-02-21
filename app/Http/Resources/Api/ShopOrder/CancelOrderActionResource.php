<?php

namespace App\Http\Resources\Api\ShopOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class CancelOrderActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $this->resource['message'],
            'status_code' => $success ? 200 : 422,
            'data' => $success ? [
                'order' => $this->resource['order'],
            ] : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 200 : 422;

        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
