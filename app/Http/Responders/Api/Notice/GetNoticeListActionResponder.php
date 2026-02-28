<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\GetNoticeListActionResource;

final class GetNoticeListActionResponder
{
    public function __invoke(array $result): GetNoticeListActionResource
    {
        return new GetNoticeListActionResource($result);
    }
}
