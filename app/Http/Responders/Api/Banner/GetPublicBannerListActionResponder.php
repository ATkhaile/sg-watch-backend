<?php

namespace App\Http\Responders\Api\Banner;

use App\Http\Resources\Api\Banner\GetPublicBannerListActionResource;

final class GetPublicBannerListActionResponder
{
    public function __invoke(array $result): GetPublicBannerListActionResource
    {
        return new GetPublicBannerListActionResource($result);
    }
}
