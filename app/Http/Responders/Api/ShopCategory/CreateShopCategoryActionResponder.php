<?php

namespace App\Http\Responders\Api\ShopCategory;

use App\Http\Resources\Api\ShopCategory\CreateShopCategoryActionResource;

final class CreateShopCategoryActionResponder
{
    public function __invoke(array $result): CreateShopCategoryActionResource
    {
        return new CreateShopCategoryActionResource($result);
    }
}
