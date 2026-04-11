<?php

namespace App\Http\Responders\Api\ShopCollection;

use App\Http\Resources\Api\ShopCollection\AdminGetCollectionsActionResource;

final class AdminGetCollectionsActionResponder
{
    public function __invoke(array $collections): AdminGetCollectionsActionResource
    {
        return new AdminGetCollectionsActionResource(['collections' => $collections]);
    }
}
