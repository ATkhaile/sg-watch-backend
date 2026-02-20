<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Chat\Entity\ConversationsListEntity;

class GetConversationsResource extends JsonResource
{
    public function __construct(private ConversationsListEntity $entity)
    {
        parent::__construct($entity);
    }

    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => '統合チャットリストを取得しました',
            'status_code' => $this->entity->getStatus(),
            'data' => [
                'conversations' => $this->entity->getConversations(),
                'pagination' => $this->entity->getPagination(),
            ],
        ];
    }
}