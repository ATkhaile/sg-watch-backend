<?php

namespace App\Http\Responders\Api\ShopBrand;

use App\Http\Resources\Api\ShopBrand\DeleteShopBrandActionResource;

final class DeleteShopBrandActionResponder
{
    public function __invoke(array $result): DeleteShopBrandActionResource
    {
        return new DeleteShopBrandActionResource($result);
    }
}
