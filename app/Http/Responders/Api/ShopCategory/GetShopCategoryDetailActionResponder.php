<?php

namespace App\Http\Responders\Api\ShopCategory;

use App\Http\Resources\Api\ShopCategory\GetShopCategoryDetailActionResource;

final class GetShopCategoryDetailActionResponder
{
    public function __invoke(array $category): GetShopCategoryDetailActionResource
    {
        return new GetShopCategoryDetailActionResource(['category' => $category]);
    }
}
