<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\GetBestSellersActionResource;

final class GetBestSellersActionResponder
{
    public function __invoke(array $products): GetBestSellersActionResource
    {
        return new GetBestSellersActionResource(['products' => $products]);
    }
}
