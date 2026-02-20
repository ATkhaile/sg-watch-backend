<?php

namespace App\Http\Responders\Api\Users;

use App\Domain\Memberships\Entity\StatusEntity;
use App\Http\Resources\Api\Users\SyncUserMembershipsActionResource;

class SyncUserMembershipsActionResponder
{
    public function __invoke(StatusEntity $entity): SyncUserMembershipsActionResource
    {
        $statusCode = $entity->getStatus();
        return new SyncUserMembershipsActionResource([
            'success' => $statusCode === 200 || $statusCode === 201,
            'message' => $entity->getMessage(),
            'status_code' => $statusCode,
        ]);
    }
}
