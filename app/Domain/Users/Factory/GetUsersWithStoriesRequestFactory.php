<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\GetUsersWithStoriesRequestEntity;
use App\Http\Requests\Api\Users\GetUsersWithStoriesRequest;

class GetUsersWithStoriesRequestFactory
{
    public function createFromRequest(GetUsersWithStoriesRequest $request): GetUsersWithStoriesRequestEntity
    {
        return new GetUsersWithStoriesRequestEntity(
            page: $request->input('page'),
            limit: $request->input('limit')
        );
    }
}
