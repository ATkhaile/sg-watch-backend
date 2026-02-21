<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\CreateProductActionResource;

final class CreateProductActionResponder
{
    public function __invoke(array $result): CreateProductActionResource
    {
        return new CreateProductActionResource($result);
    }
}
