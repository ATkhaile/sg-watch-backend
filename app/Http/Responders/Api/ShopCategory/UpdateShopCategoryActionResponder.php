<?php

namespace App\Http\Responders\Api\ShopCategory;

use App\Http\Resources\Api\ShopCategory\UpdateShopCategoryActionResource;

final class UpdateShopCategoryActionResponder
{
    public function __invoke(array $result): UpdateShopCategoryActionResource
    {
        return new UpdateShopCategoryActionResource($result);
    }
}
