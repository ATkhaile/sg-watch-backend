<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\GetOrderListActionResource;

final class GetOrderListActionResponder
{
    public function __invoke(array $result): GetOrderListActionResource
    {
        return new GetOrderListActionResource($result);
    }
}
