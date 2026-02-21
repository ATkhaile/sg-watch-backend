<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminUpdateOrderStatusActionResource;

final class AdminUpdateOrderStatusActionResponder
{
    public function __invoke(array $result): AdminUpdateOrderStatusActionResource
    {
        return new AdminUpdateOrderStatusActionResource($result);
    }
}
