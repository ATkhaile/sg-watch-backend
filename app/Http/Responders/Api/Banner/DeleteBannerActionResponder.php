<?php

namespace App\Http\Responders\Api\Banner;

use App\Http\Resources\Api\Banner\DeleteBannerActionResource;

final class DeleteBannerActionResponder
{
    public function __invoke(array $result): DeleteBannerActionResource
    {
        return new DeleteBannerActionResource($result);
    }
}
