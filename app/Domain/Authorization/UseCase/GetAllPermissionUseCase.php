<?php

namespace App\Domain\Authorization\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Authorization\Entity\PermissionsEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllPermissionUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-permission';

    public function __construct(
        private PermissionRepository $permissionRepository
    ) {
    }

    public function __invoke(PermissionsEntity $entity): PermissionsEntity
    {
        $this->authorize();

        // Check if requesting unique usecase groups
        if ($entity->getUsecaseGroup() === 'unique') {
            $usecaseGroups = $this->permissionRepository->getDistinctUsecaseGroups();

            $permissions = $usecaseGroups->map(function ($usecaseGroup) {
                return [
                    'id' => $usecaseGroup->id,
                    'usecase_group' => $usecaseGroup->usecase_group,
                ];
            })->toArray();

            $entity->setPermissions($permissions);
            $entity->setPagination([
                'total' => count($permissions),
                'count' => count($permissions),
                'per_page' => count($permissions),
                'current_page' => 1,
                'total_pages' => 1,
            ]);
            $entity->setStatus(StatusCode::OK);
            return $entity;
        }

        // Normal permission list
        $data = $this->permissionRepository->getAllPermission($entity);

        $permissions = collect($data->items())->map(function ($permissions) {
            return [
                'id' => $permissions->id,
                'name' => $permissions->name,
                'display_name' => $permissions->display_name,
                'description' => $permissions->description,
                'usecase_group' => $permissions->usecase_group,
                'is_active' => $permissions->is_active ?? true,
                'created_at' => $permissions->created_at,
                'updated_at' => $permissions->updated_at,
            ];
        })->toArray();

        $entity->setPermissions($permissions);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
