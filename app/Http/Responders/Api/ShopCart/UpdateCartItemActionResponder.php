<?php

namespace App\Http\Responders\Api\ShopCart;

use App\Http\Resources\Api\ShopCart\UpdateCartItemActionResource;

final class UpdateCartItemActionResponder
{
    public function __invoke(array $result): UpdateCartItemActionResource
    {
        return new UpdateCartItemActionResource($result);
    }
}
