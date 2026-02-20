<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Entity\ConversationsListEntity;
use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Components\CommonComponent;
use App\Enums\StatusCode;

class GetConversationsUseCase
{
    public function __construct(
        private ChatMessageRepository $chatMessageRepository
    ) {
    }

    public function execute(ConversationsListEntity $entity): ConversationsListEntity
    {
        $result = $this->chatMessageRepository->getConversations($entity);

        $entity->setConversations($result['conversations']);

        // Set pagination data with unread counts
        $paginationData = [
            'total' => $result['total'],
            'count' => count($result['conversations']),
            'per_page' => $result['per_page'],
            'current_page' => $result['current_page'],
            'total_pages' => $result['total_pages'],
            'total_unread_count' => $result['total_unread_count'],
        ];

        $entity->setPagination($paginationData);
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}