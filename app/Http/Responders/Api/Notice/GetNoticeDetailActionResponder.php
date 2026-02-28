<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\GetNoticeDetailActionResource;

final class GetNoticeDetailActionResponder
{
    public function __invoke(array $notice): GetNoticeDetailActionResource
    {
        return new GetNoticeDetailActionResource(['notice' => $notice]);
    }
}
