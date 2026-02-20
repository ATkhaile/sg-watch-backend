<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;
use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\AttachPermissionToRoleRequestEntity;

final class AttachPermissionToRoleUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'attach-permission-to-role';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(AttachPermissionToRoleRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        $roleId = $requestEntity->getRoleId();
        $permissionIds = $requestEntity->getPermissionIds();

        if ($this->permissionRepository->attachToRole($roleId, $permissionIds)) {
            return new StatusEntity(StatusCode::OK, __('authorization.permission.attach.success'));
        }

        return new StatusEntity(StatusCode::INTERNAL_ERR, __('authorization.permission.attach.failed'));
    }
}
