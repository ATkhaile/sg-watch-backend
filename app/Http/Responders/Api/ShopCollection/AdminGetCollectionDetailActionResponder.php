<?php

namespace App\Http\Responders\Api\ShopCollection;

use App\Http\Resources\Api\ShopCollection\AdminGetCollectionDetailActionResource;

final class AdminGetCollectionDetailActionResponder
{
    public function __invoke(?array $result): AdminGetCollectionDetailActionResource
    {
        return new AdminGetCollectionDetailActionResource($result);
    }
}
