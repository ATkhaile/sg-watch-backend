<?php

namespace App\Http\Responders\Api\Chat;

use App\Domain\Chat\Entity\ChatMessageListEntity;
use App\Http\Resources\Api\Chat\GetAllChatHistoryResource;

final class GetAllChatHistoryResponder
{
    public function __invoke(ChatMessageListEntity $chatListEntity): GetAllChatHistoryResource
    {
        $resource = $this->makeResource($chatListEntity);
        return new GetAllChatHistoryResource($resource);
    }

    public function makeResource(ChatMessageListEntity $chatListEntity)
    {
        return [
            'status_code' => $chatListEntity->getStatus(),
            'data' => [
                'messages' => $chatListEntity->getMessages(),
                'unread_count' => $chatListEntity->getUnreadCount(),
                'pagination' => $chatListEntity->getPagination(),
            ],
        ];
    }
}
