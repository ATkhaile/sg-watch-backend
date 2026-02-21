<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\GetOrderDetailActionResource;

final class GetOrderDetailActionResponder
{
    public function __invoke(array $order): GetOrderDetailActionResource
    {
        return new GetOrderDetailActionResource(['order' => $order]);
    }
}
