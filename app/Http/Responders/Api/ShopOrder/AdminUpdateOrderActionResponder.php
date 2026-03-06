<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminUpdateOrderActionResource;

final class AdminUpdateOrderActionResponder
{
    public function __invoke(array $result): AdminUpdateOrderActionResource
    {
        return new AdminUpdateOrderActionResource($result);
    }
}
