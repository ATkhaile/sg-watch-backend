<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\CreateNoticeActionResource;

final class CreateNoticeActionResponder
{
    public function __invoke(array $result): CreateNoticeActionResource
    {
        return new CreateNoticeActionResource($result);
    }
}
