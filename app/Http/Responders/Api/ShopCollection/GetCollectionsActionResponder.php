<?php

namespace App\Http\Responders\Api\ShopCollection;

use App\Http\Resources\Api\ShopCollection\GetCollectionsActionResource;

final class GetCollectionsActionResponder
{
    public function __invoke(array $collections): GetCollectionsActionResource
    {
        return new GetCollectionsActionResource(['collections' => $collections]);
    }
}
