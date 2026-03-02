<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\GetFeaturedProductsActionResource;

final class GetFeaturedProductsActionResponder
{
    public function __invoke(array $products): GetFeaturedProductsActionResource
    {
        return new GetFeaturedProductsActionResource(['products' => $products]);
    }
}
