<?php

namespace App\Http\Responders\Api\ShopCart;

use App\Http\Resources\Api\ShopCart\GetCartActionResource;

final class GetCartActionResponder
{
    public function __invoke(array $cart): GetCartActionResource
    {
        return new GetCartActionResource([
            'cart' => $cart,
        ]);
    }
}
