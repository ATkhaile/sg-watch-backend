<?php

namespace App\Http\Responders\Api\Notice;

use App\Http\Resources\Api\Notice\DeleteNoticeActionResource;

final class DeleteNoticeActionResponder
{
    public function __invoke(array $result): DeleteNoticeActionResource
    {
        return new DeleteNoticeActionResource($result);
    }
}
