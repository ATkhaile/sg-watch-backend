<?php

namespace App\Http\Responders\Api\ShopOrder;

use App\Http\Resources\Api\ShopOrder\AdminCreateOrderActionResource;

final class AdminCreateOrderActionResponder
{
    public function __invoke(array $result): AdminCreateOrderActionResource
    {
        return new AdminCreateOrderActionResource($result);
    }
}
