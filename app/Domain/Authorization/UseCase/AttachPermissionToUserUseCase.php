<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;
use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\AttachPermissionToUserRequestEntity;

final class AttachPermissionToUserUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'attach-permission-to-user';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(AttachPermissionToUserRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        $userId = $requestEntity->getUserId();
        $permissionIds = $requestEntity->getPermissionIds();

        if ($this->permissionRepository->attachToUser($userId, $permissionIds)) {
            return new StatusEntity(StatusCode::OK, __('authorization.permission.attach.success'));
        }

        return new StatusEntity(StatusCode::INTERNAL_ERR, __('authorization.permission.attach.failed'));
    }
}
