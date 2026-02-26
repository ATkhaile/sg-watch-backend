<?php

namespace App\Http\Responders\Api\Banner;

use App\Http\Resources\Api\Banner\GetBannerDetailActionResource;

final class GetBannerDetailActionResponder
{
    public function __invoke(array $banner): GetBannerDetailActionResource
    {
        return new GetBannerDetailActionResource(['banner' => $banner]);
    }
}
