<?php

namespace App\Http\Responders\Api\ShopCollection;

use App\Http\Resources\Api\ShopCollection\CreateCollectionActionResource;

final class CreateCollectionActionResponder
{
    public function __invoke(array $result): CreateCollectionActionResource
    {
        return new CreateCollectionActionResource($result);
    }
}
