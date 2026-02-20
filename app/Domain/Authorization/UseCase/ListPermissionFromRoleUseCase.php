<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\ListPermissionFromRoleRequestEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Exceptions\Domain\NotFoundResourceException;
use App\Enums\StatusCode;

final class ListPermissionFromRoleUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-permission-from-role';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(ListPermissionFromRoleRequestEntity $requestEntity): array
    {
        $this->authorize();

        $role_permissions = $this->permissionRepository->listPermissionFromRole($requestEntity->getRoleId());

        if ($role_permissions->isEmpty()) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return [
            'status_code' => StatusCode::OK,
            'permissions' => $role_permissions
        ];
    }
}
