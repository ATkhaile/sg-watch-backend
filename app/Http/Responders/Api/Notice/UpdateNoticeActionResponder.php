<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\UpdateNoticeActionResource;

final class UpdateNoticeActionResponder
{
    public function __invoke(array $result): UpdateNoticeActionResource
    {
        return new UpdateNoticeActionResource($result);
    }
}
