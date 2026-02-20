<?php

namespace App\Domain\Chat\Factory;

use App\Domain\Chat\Entity\UsersEntity;
use Illuminate\Http\Request;

class GetUsersForChatRequestFactory
{
    public function createFromRequest(Request $request): UsersEntity
    {
        return new UsersEntity(
            users: [],
            pagination: [],
            statusCode: 200,
            search: $request->input('search'),
            page: (int)$request->input('page', 1),
            limit: (int)$request->input('limit', 10)
        );
    }
}
