<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\RestoreProductsByBrandActionResource;

final class RestoreProductsByBrandActionResponder
{
    public function __invoke(array $result): RestoreProductsByBrandActionResource
    {
        return new RestoreProductsByBrandActionResource($result);
    }
}
