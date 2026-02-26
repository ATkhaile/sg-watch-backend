<?php

namespace App\Http\Responders\Api\Banner;

use App\Http\Resources\Api\Banner\GetBannerListActionResource;

final class GetBannerListActionResponder
{
    public function __invoke(array $result): GetBannerListActionResource
    {
        return new GetBannerListActionResource($result);
    }
}
