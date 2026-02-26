<?php

namespace App\Http\Responders\Api\Banner;

use App\Http\Resources\Api\Banner\UpdateBannerActionResource;

final class UpdateBannerActionResponder
{
    public function __invoke(array $result): UpdateBannerActionResource
    {
        return new UpdateBannerActionResource($result);
    }
}
