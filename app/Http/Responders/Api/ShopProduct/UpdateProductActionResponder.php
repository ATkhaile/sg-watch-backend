<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\UpdateProductActionResource;

final class UpdateProductActionResponder
{
    public function __invoke(array $result): UpdateProductActionResource
    {
        return new UpdateProductActionResource($result);
    }
}
