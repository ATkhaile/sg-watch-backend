<?php

namespace App\Domain\Chat\Factory;

use App\Domain\Chat\Entity\ChatMessageListEntity;
use App\Http\Requests\Api\Chat\GetChatHistoryRequest;

class GetChatHistoryRequestFactory
{
    public function createFromRequest(GetChatHistoryRequest $request): ChatMessageListEntity
    {
        return new ChatMessageListEntity(
            userId: (int) auth()->user()->id,
            receiverId: (int) $request->input('receiver_id', 0),
            limit: (int) $request->input('limit', 100),
            page: (int) $request->input('page', 1)
        );
    }
}
