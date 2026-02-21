<?php

namespace App\Domain\Sessions\Factory;

use App\Domain\Sessions\Entity\GetAllSessionsEntity;
use App\Http\Requests\Api\Sessions\GetAllSessionsRequest;

class GetAllSessionsRequestFactory
{
    public function createFromRequest(GetAllSessionsRequest $request): GetAllSessionsEntity
    {
        return new GetAllSessionsEntity(
            userId: $request->input('user_id'),
            filterStatus: $request->input('status'),
            sortBy: $request->input('sort_by', 'id'),
            sortDirection: $request->input('sort_direction', 'desc'),
            perPage: $request->input('per_page', 10),
            page: $request->input('page', 1)
        );
    }
}
