<?php

namespace App\Http\Responders\Api\ShopBrand;

use App\Http\Resources\Api\ShopBrand\GetShopBrandListActionResource;

final class GetShopBrandListActionResponder
{
    public function __invoke(array $result): GetShopBrandListActionResource
    {
        return new GetShopBrandListActionResource($result);
    }
}
