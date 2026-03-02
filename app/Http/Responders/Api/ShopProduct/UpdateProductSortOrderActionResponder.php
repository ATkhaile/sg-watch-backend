<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\UpdateProductSortOrderActionResource;

final class UpdateProductSortOrderActionResponder
{
    public function __invoke(array $result): UpdateProductSortOrderActionResource
    {
        return new UpdateProductSortOrderActionResource($result);
    }
}
