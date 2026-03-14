<?php

namespace App\Http\Resources\Api\BigSale;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateBigSaleActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => $this->resource['message'],
            'status_code' => 201,
            'data' => [
                'big_sale' => $this->resource['big_sale'],
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(201);
    }
}
