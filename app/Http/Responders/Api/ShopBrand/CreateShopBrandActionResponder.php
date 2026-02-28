<?php

namespace App\Http\Responders\Api\ShopBrand;

use App\Http\Resources\Api\ShopBrand\CreateShopBrandActionResource;

final class CreateShopBrandActionResponder
{
    public function __invoke(array $result): CreateShopBrandActionResource
    {
        return new CreateShopBrandActionResource($result);
    }
}
