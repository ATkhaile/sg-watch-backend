<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\MarkNoticeAsReadActionResource;

final class MarkNoticeAsReadActionResponder
{
    public function __invoke(array $result): MarkNoticeAsReadActionResource
    {
        return new MarkNoticeAsReadActionResource($result);
    }
}
