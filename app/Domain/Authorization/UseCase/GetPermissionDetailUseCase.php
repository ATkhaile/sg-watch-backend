<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\PermissionDetailEntity;
use App\Domain\Authorization\Entity\GetPermissionDetailRequestEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Exceptions\Domain\NotFoundResourceException;

final class GetPermissionDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-permission';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(GetPermissionDetailRequestEntity $requestEntity): PermissionDetailEntity
    {
        $this->authorize();

        $permissionId = $requestEntity->getId();
        $permission = $this->permissionRepository->getPermissionDetail($permissionId);

        if (!$permission) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return $permission;
    }
}
