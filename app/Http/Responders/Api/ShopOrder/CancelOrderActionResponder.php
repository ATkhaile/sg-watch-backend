<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\CancelOrderActionResource;

final class CancelOrderActionResponder
{
    public function __invoke(array $result): CancelOrderActionResource
    {
        return new CancelOrderActionResource($result);
    }
}
