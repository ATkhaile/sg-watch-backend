<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\DeleteProductActionResource;

final class DeleteProductActionResponder
{
    public function __invoke(array $result): DeleteProductActionResource
    {
        return new DeleteProductActionResource($result);
    }
}
