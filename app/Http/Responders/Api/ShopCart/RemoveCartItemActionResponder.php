<?php

namespace App\Http\Responders\Api\ShopCart;

use App\Http\Resources\Api\ShopCart\RemoveCartItemActionResource;

final class RemoveCartItemActionResponder
{
    public function __invoke(array $result): RemoveCartItemActionResource
    {
        return new RemoveCartItemActionResource($result);
    }
}
