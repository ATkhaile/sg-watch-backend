<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminDeleteOrderActionResource;

final class AdminDeleteOrderActionResponder
{
    public function __invoke(array $result): AdminDeleteOrderActionResource
    {
        return new AdminDeleteOrderActionResource($result);
    }
}
