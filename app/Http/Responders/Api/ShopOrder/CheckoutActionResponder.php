<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\CheckoutActionResource;

final class CheckoutActionResponder
{
    public function __invoke(array $result): CheckoutActionResource
    {
        return new CheckoutActionResource($result);
    }
}
