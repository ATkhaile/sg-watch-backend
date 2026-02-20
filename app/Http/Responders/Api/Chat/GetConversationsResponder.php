<?php

namespace App\Http\Responders\Api\Chat;

use App\Domain\Chat\Entity\ConversationsListEntity;
use App\Http\Resources\Api\Chat\GetConversationsResource;

class GetConversationsResponder
{
    public function __invoke(ConversationsListEntity $entity): GetConversationsResource
    {
        return new GetConversationsResource($entity);
    }
}