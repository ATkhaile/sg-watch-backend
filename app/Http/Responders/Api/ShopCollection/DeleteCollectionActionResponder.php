<?php

namespace App\Http\Responders\Api\ShopCollection;

use App\Http\Resources\Api\ShopCollection\DeleteCollectionActionResource;

final class DeleteCollectionActionResponder
{
    public function __invoke(array $result): DeleteCollectionActionResource
    {
        return new DeleteCollectionActionResource($result);
    }
}
