<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;
use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\CreatePermissionRequestEntity;

final class CreatePermissionUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-permission';

    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function __invoke(CreatePermissionRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->permissionRepository->store($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('authorization.permission.create.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('authorization.permission.create.failed')
            );
        }
    }
}
