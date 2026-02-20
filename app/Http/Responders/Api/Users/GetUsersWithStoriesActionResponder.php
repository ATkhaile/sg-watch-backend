<?php

namespace App\Http\Responders\Api\Users;

use App\Domain\Users\Entity\GetUsersWithStoriesEntity;
use App\Http\Resources\Api\Users\GetUsersWithStoriesActionResource;

final class GetUsersWithStoriesActionResponder
{
    public function __invoke(GetUsersWithStoriesEntity $entity): GetUsersWithStoriesActionResource
    {
        $resource = $this->makeResource($entity);
        return new GetUsersWithStoriesActionResource($resource);
    }

    public function makeResource(GetUsersWithStoriesEntity $entity)
    {
        return [
            'status_code' => $entity->getStatus(),
            'data' => [
                'users' => $entity->getUsers(),
                'pagination' => $entity->getPagination(),
            ],
        ];
    }
}
