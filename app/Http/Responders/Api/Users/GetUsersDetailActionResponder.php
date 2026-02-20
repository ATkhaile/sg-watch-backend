<?php

namespace App\Http\Responders\Api\Users;

use App\Domain\Users\Entity\UsersDetailEntity;
use App\Http\Resources\Api\Users\GetUsersDetailActionResource;

final class GetUsersDetailActionResponder
{
    public function __invoke(UsersDetailEntity $usersEntity): GetUsersDetailActionResource
    {
        $resource = $this->makeResource($usersEntity);
        return new GetUsersDetailActionResource($resource);
    }

    public function makeResource(UsersDetailEntity $usersEntity)
    {
        return [
            'users' => $usersEntity->jsonSerialize(),
            'status_code' => $usersEntity->getStatus(),
        ];
    }
}
