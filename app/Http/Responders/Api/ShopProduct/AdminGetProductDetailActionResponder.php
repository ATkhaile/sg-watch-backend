<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\AdminGetProductDetailActionResource;

final class AdminGetProductDetailActionResponder
{
    public function __invoke(array $product): AdminGetProductDetailActionResource
    {
        return new AdminGetProductDetailActionResource(['product' => $product]);
    }
}
