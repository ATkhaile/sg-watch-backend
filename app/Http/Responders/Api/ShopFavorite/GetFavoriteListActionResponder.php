<?php

namespace App\Http\Responders\Api\ShopFavorite;

use App\Http\Resources\Api\ShopFavorite\GetFavoriteListActionResource;

final class GetFavoriteListActionResponder
{
    public function __invoke(array $favorites): GetFavoriteListActionResource
    {
        return new GetFavoriteListActionResource([
            'favorites' => $favorites,
        ]);
    }
}
