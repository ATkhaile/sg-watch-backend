<?php

namespace App\Http\Responders\Api\ShopCollection;

use App\Http\Resources\Api\ShopCollection\UpdateCollectionActionResource;

final class UpdateCollectionActionResponder
{
    public function __invoke(array $result): UpdateCollectionActionResource
    {
        return new UpdateCollectionActionResource($result);
    }
}
