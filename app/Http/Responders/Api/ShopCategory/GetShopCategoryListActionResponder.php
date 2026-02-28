<?php

namespace App\Http\Responders\Api\ShopCategory;

use App\Http\Resources\Api\ShopCategory\GetShopCategoryListActionResource;

final class GetShopCategoryListActionResponder
{
    public function __invoke(array $result): GetShopCategoryListActionResource
    {
        return new GetShopCategoryListActionResource($result);
    }
}
