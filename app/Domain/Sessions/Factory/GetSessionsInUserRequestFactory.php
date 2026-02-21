<?php

namespace App\Domain\Sessions\Factory;

use App\Domain\Sessions\Entity\GetSessionsInUserEntity;
use App\Http\Requests\Api\Sessions\GetSessionsInUserRequest;

class GetSessionsInUserRequestFactory
{
    public function createFromRequest(GetSessionsInUserRequest $request): GetSessionsInUserEntity
    {
        return new GetSessionsInUserEntity(
            filterStatus: $request->input('status'),
            sortBy: $request->input('sort_by', 'id'),
            sortDirection: $request->input('sort_direction', 'desc'),
            perPage: $request->input('per_page', 10),
            page: $request->input('page', 1)
        );
    }
}
