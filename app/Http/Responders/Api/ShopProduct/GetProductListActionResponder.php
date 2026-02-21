<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\GetProductListActionResource;

final class GetProductListActionResponder
{
    public function __invoke(array $result): GetProductListActionResource
    {
        return new GetProductListActionResource($result);
    }
}
