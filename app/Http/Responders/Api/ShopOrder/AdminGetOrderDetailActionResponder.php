<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminGetOrderDetailActionResource;

final class AdminGetOrderDetailActionResponder
{
    public function __invoke(array $order): AdminGetOrderDetailActionResource
    {
        return new AdminGetOrderDetailActionResource(['order' => $order]);
    }
}
