<?php

namespace App\Domain\Chat\Factory;

use App\Domain\Chat\Entity\ConversationsListEntity;
use App\Http\Requests\Api\Chat\GetConversationsRequest;

class GetConversationsRequestFactory
{
    public function createFromRequest(GetConversationsRequest $request): ConversationsListEntity
    {
        $user = auth()->user();

        if (!$user || !$user->id) {
            throw new \Exception('User not authenticated', 401);
        }

        return new ConversationsListEntity(
            userId: (int) $user->id,
            page: $request->getPage(),
            limit: $request->getLimit(),
            search: $request->getSearch(),
            messageSearch: $request->getMessageSearch()
        );
    }
}
