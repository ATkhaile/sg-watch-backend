<?php

namespace App\Http\Responders\Api\ShopBrand;

use App\Http\Resources\Api\ShopBrand\UpdateShopBrandActionResource;

final class UpdateShopBrandActionResponder
{
    public function __invoke(array $result): UpdateShopBrandActionResource
    {
        return new UpdateShopBrandActionResource($result);
    }
}
