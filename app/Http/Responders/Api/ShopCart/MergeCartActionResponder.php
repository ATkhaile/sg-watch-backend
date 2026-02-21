<?php

namespace App\Http\Responders\Api\ShopCart;

use App\Http\Resources\Api\ShopCart\MergeCartActionResource;

final class MergeCartActionResponder
{
    public function __invoke(array $cart): MergeCartActionResource
    {
        return new MergeCartActionResource([
            'cart' => $cart,
        ]);
    }
}
