<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\RolesEntity;
use App\Domain\Authorization\Repository\RoleRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllRoleUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-role';

    public function __construct(
        private RoleRepository $roleRepository
    ) {
    }

    public function __invoke(RolesEntity $entity): RolesEntity
    {
        $this->authorize();

        $data = $this->roleRepository->getAllRole($entity);

        $roles = collect($data->items())->map(function ($roles) {
            return [
                'id' => $roles->id,
                'name' => $roles->name,
                'display_name' => $roles->display_name,
                'description' => $roles->description,
                'users_count' => $roles->users_count ?? 0,
                'created_at' => $roles->created_at,
                'updated_at' => $roles->updated_at,
            ];
        })->toArray();

        $entity->setRoles($roles);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
