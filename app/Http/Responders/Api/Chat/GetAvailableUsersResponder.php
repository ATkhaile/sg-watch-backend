<?php

namespace App\Http\Responders\Api\Chat;

use App\Domain\Chat\Entity\AvailableUsersListEntity;
use App\Http\Resources\Api\Chat\GetAvailableUsersResource;

final class GetAvailableUsersResponder
{
    public function __invoke(AvailableUsersListEntity $entity): GetAvailableUsersResource
    {
        $resource = $this->makeResource($entity);
        return new GetAvailableUsersResource($resource);
    }

    public function makeResource(AvailableUsersListEntity $entity): array
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