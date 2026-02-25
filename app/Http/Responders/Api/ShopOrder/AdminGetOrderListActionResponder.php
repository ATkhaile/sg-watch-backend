<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminGetOrderListActionResource;

final class AdminGetOrderListActionResponder
{
    public function __invoke(array $result): AdminGetOrderListActionResource
    {
        return new AdminGetOrderListActionResource($result);
    }
}
