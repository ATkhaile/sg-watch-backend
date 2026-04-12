<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\DeleteProductsByBrandActionResource;

final class DeleteProductsByBrandActionResponder
{
    public function __invoke(array $result): DeleteProductsByBrandActionResource
    {
        return new DeleteProductsByBrandActionResource($result);
    }
}
