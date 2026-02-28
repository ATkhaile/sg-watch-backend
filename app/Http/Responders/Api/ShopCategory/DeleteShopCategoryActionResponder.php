<?php

namespace App\Http\Responders\Api\ShopCategory;

use App\Http\Resources\Api\ShopCategory\DeleteShopCategoryActionResource;

final class DeleteShopCategoryActionResponder
{
    public function __invoke(array $result): DeleteShopCategoryActionResource
    {
        return new DeleteShopCategoryActionResource($result);
    }
}
