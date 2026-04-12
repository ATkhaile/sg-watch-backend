<?php

namespace App\Http\Responders\Api\ShopInventoryHistory;

use App\Http\Resources\Api\ShopInventoryHistory\GetInventoryHistoryActionResource;

final class GetInventoryHistoryActionResponder
{
    public function __invoke(array $result): GetInventoryHistoryActionResource
    {
        return new GetInventoryHistoryActionResource($result);
    }
}
