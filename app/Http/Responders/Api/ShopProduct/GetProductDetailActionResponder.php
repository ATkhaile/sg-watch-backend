<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\GetProductDetailActionResource;

final class GetProductDetailActionResponder
{
    public function __invoke(array $product): GetProductDetailActionResource
    {
        return new GetProductDetailActionResource([
            'product' => $product,
        ]);
    }
}
