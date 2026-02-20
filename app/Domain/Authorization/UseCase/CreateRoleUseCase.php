<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Repository\RoleRepository;
use App\Enums\StatusCode;
use App\Domain\Authorization\Entity\StatusEntity;
use App\Domain\Authorization\Entity\CreateRoleRequestEntity;

final class CreateRoleUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-role';

    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(CreateRoleRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->roleRepository->store($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('authorization.role.create.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('authorization.role.create.failed')
            );
        }
    }
}
