<?php

namespace App\Domain\Chat\Factory;

use App\Domain\Chat\Entity\AvailableUsersListEntity;
use App\Http\Requests\Api\Chat\GetAvailableUsersRequest;
use App\Models\User;

class GetAvailableUsersRequestFactory
{
    public function create(GetAvailableUsersRequest $request, User $user): AvailableUsersListEntity
    {
        return new AvailableUsersListEntity(
            users: [],
            pagination: [],
            statusCode: 200,
            userId: $user->id,
            search: $request->input('search'),
            limit: $request->input('limit', 20),
            page: $request->input('page', 1),
            messageSearch: $request->input('message_search')
        );
    }
}
