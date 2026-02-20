<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;
use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\RevokePermissionToUserRequestEntity;

final class RevokePermissionToUserUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'revoke-permission-to-user';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(RevokePermissionToUserRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        $userId = $requestEntity->getUserId();
        $permissionIds = $requestEntity->getPermissionIds();

        if ($this->permissionRepository->revokeToUser($userId, $permissionIds)) {
            return new StatusEntity(StatusCode::OK, __('authorization.permission.attach.success'));
        }

        return new StatusEntity(StatusCode::INTERNAL_ERR, __('authorization.permission.attach.failed'));
    }
}
