<?php

namespace App\Http\Responders\Api\ShopProduct;

use App\Http\Resources\Api\ShopProduct\AdminGetProductListActionResource;

final class AdminGetProductListActionResponder
{
    public function __invoke(array $result): AdminGetProductListActionResource
    {
        return new AdminGetProductListActionResource($result);
    }
}
