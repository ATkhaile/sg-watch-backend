<?php

namespace App\Http\Responders\Api\ShopFavorite;

use App\Http\Resources\Api\ShopFavorite\ToggleFavoriteActionResource;

final class ToggleFavoriteActionResponder
{
    public function __invoke(array $result): ToggleFavoriteActionResource
    {
        return new ToggleFavoriteActionResource($result);
    }
}
