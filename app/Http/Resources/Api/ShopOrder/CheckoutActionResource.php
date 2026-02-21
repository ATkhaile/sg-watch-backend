<?php

namespace App\Http\Resources\Api\ShopOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutActionResource extends JsonResource
{
    public function toArray($request): array
    {
        $success = $this->resource['success'];

        return [
            'message' => $this->resource['message'],
            'status_code' => $success ? 201 : 422,
            'data' => $success ? [
                'order' => $this->resource['order'],
            ] : null,
        ];
    }

    public function toResponse($request)
    {
        $statusCode = $this->resource['success'] ? 201 : 422;

        return parent::toResponse($request)->setStatusCode($statusCode);
    }
}
