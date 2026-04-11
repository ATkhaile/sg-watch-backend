<?php

namespace App\Http\Responders\Api\ShopProductColor;

use App\Http\Resources\Api\ShopProductColor\GetProductColorsActionResource;

final class GetProductColorsActionResponder
{
    public function __invoke(array $result): GetProductColorsActionResource
    {
        return new GetProductColorsActionResource($result);
    }
}
