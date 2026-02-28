<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\GetMemberNoticesActionResource;

final class GetMemberNoticesActionResponder
{
    public function __invoke(array $result): GetMemberNoticesActionResource
    {
        return new GetMemberNoticesActionResource($result);
    }
}
