<?php

namespace App\Http\Responders\Api\Chat;

use App\Domain\Chat\Entity\UsersEntity;
use App\Http\Resources\Api\Chat\GetUsersForChatResource;

final class GetUsersForChatResponder
{
    public function __invoke(UsersEntity $availableUsersEntity): GetUsersForChatResource
    {
        $resource = $this->makeResource($availableUsersEntity);
        return new GetUsersForChatResource($resource);
    }

    public function makeResource(UsersEntity $availableUsersEntity)
    {
        return [
            'status_code' => $availableUsersEntity->getStatus(),
            'data' => [
                'users' => $availableUsersEntity->getUsers(),
                'pagination' => $availableUsersEntity->getPagination(),
            ],
        ];
    }
}