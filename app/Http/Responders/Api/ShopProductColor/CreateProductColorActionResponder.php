<?php

namespace App\Http\Responders\Api\ShopProductColor;

use App\Http\Resources\Api\ShopProductColor\CreateProductColorActionResource;

final class CreateProductColorActionResponder
{
    public function __invoke(array $result): CreateProductColorActionResource
    {
        return new CreateProductColorActionResource($result);
    }
}
