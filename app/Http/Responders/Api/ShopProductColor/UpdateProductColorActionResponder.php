<?php

namespace App\Http\Responders\Api\ShopProductColor;

use App\Http\Resources\Api\ShopProductColor\UpdateProductColorActionResource;

final class UpdateProductColorActionResponder
{
    public function __invoke(array $result): UpdateProductColorActionResource
    {
        return new UpdateProductColorActionResource($result);
    }
}
