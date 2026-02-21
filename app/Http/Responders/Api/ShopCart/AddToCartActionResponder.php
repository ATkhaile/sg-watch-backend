<?php

namespace App\Http\Responders\Api\ShopCart;

use App\Http\Resources\Api\ShopCart\AddToCartActionResource;

final class AddToCartActionResponder
{
    public function __invoke(array $result): AddToCartActionResource
    {
        return new AddToCartActionResource($result);
    }
}
