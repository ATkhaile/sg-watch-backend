<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\AdminGetFeaturedProductsActionResource;

final class AdminGetFeaturedProductsActionResponder
{
    public function __invoke(array $products): AdminGetFeaturedProductsActionResource
    {
        return new AdminGetFeaturedProductsActionResource(['products' => $products]);
    }
}
