<?php

namespace App\Http\Responders\Api\Banner;

use App\Http\Resources\Api\Banner\CreateBannerActionResource;

final class CreateBannerActionResponder
{
    public function __invoke(array $result): CreateBannerActionResource
    {
        return new CreateBannerActionResource($result);
    }
}
