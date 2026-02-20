<?php

namespace App\Http\Responders\Api\Users;

use App\Domain\Users\Entity\UsersEntity;
use App\Http\Resources\Api\Users\GetAllUsersActionResource;

final class GetAllUsersActionResponder
{
    public function __invoke(UsersEntity $usersEntity): GetAllUsersActionResource
    {
        $resource = $this->makeResource($usersEntity);
        return new GetAllUsersActionResource($resource);
    }

    public function makeResource(UsersEntity $usersEntity)
    {
        return [
            'status_code' => $usersEntity->getStatus(),
            'data' => [
                'users' => $usersEntity->getUsers(),
                'pagination' =>  $usersEntity->getPagination(),
            ],
        ];
    }
}
