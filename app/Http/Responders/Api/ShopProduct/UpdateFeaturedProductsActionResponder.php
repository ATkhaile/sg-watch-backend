<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\UpdateFeaturedProductsActionResource;

final class UpdateFeaturedProductsActionResponder
{
    public function __invoke(array $result): UpdateFeaturedProductsActionResource
    {
        return new UpdateFeaturedProductsActionResource($result);
    }
}
