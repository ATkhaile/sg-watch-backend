<?php

namespace App\Http\Responders\Api\ShopBrand;

use App\Http\Resources\Api\ShopBrand\GetShopBrandDetailActionResource;

final class GetShopBrandDetailActionResponder
{
    public function __invoke(array $brand): GetShopBrandDetailActionResource
    {
        return new GetShopBrandDetailActionResource(['brand' => $brand]);
    }
}
