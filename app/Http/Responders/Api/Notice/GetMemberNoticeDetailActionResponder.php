<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\GetMemberNoticeDetailActionResource;

final class GetMemberNoticeDetailActionResponder
{
    public function __invoke(array $notice): GetMemberNoticeDetailActionResource
    {
        return new GetMemberNoticeDetailActionResource($notice);
    }
}
