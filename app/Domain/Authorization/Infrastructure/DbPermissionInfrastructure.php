<?php

namespace App\Domain\Authorization\Infrastructure;

use App\Domain\Authorization\Entity\PermissionsEntity;
use App\Domain\Authorization\Repository\PermissionRepository;
use App\Domain\Authorization\Entity\PermissionDetailEntity;
use App\Domain\Authorization\Entity\CreatePermissionRequestEntity;
use App\Domain\Authorization\Entity\UpdatePermissionRequestEntity;
use App\Domain\Authorization\Entity\DeletePermissionRequestEntity;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use App\Enums\StatusCode;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class DbPermissionInfrastructure implements PermissionRepository
{
    private Permission $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function getAllPermission(PermissionsEntity $requestEntity): LengthAwarePaginator
    {
        $query = Permission::query();

        #Search
        if ($searchName = $requestEntity->getSearchName()) {
            $searchName = trim($searchName);
            $operatorName = $requestEntity->getSearchNameLike() ? 'LIKE' : '=';
            $searchValueName = $requestEntity->getSearchNameLike() ? "%{$searchName}%" : $searchName;

            if ($requestEntity->getSearchNameNot()) {
                $query->whereRaw('LOWER(TRIM(name)) != ?', [strtolower($searchName)]);
            } else {
                if ($requestEntity->getSearchNameLike()) {
                    $query->whereRaw('LOWER(TRIM(name)) LIKE ?', [strtolower($searchValueName)]);
                } else {
                    $query->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($searchValueName)]);
                }
            }
        }

        if ($searchUsecaseGroup = $requestEntity->getSearchUsecaseGroup()) {
            $searchUsecaseGroup = trim($searchUsecaseGroup);
            $operatorUsecaseGroup = $requestEntity->getSearchUsecaseGroupLike() ? 'LIKE' : '=';
            $searchValueUsecaseGroup = $requestEntity->getSearchUsecaseGroupLike() ? "%{$searchUsecaseGroup}%" : $searchUsecaseGroup;

            if ($requestEntity->getSearchUsecaseGroupNot()) {
                $query->whereRaw('LOWER(TRIM(usecase_group)) != ?', [strtolower($searchUsecaseGroup)]);
            } else {
                if ($requestEntity->getSearchUsecaseGroupLike()) {
                    $query->whereRaw('LOWER(TRIM(usecase_group)) LIKE ?', [strtolower($searchValueUsecaseGroup)]);
                } else {
                    $query->whereRaw('LOWER(TRIM(usecase_group)) = ?', [strtolower($searchValueUsecaseGroup)]);
                }
            }
        }

        #Sort
        $sortOrder = $requestEntity->getSortOrder();
        if ($sortOrder && !empty($sortOrder)) {
            foreach ($sortOrder as $param) {
                switch ($param) {
                    case 'sort_name':
                        if ($sortName = $requestEntity->getSortName()) {
                            $query->orderBy('name', $sortName);
                        }
                        break;
                    case 'sort_usecase_group':
                        if ($sortUsecaseGroup = $requestEntity->getSortUsecaseGroup()) {
                            $query->orderBy('usecase_group', $sortUsecaseGroup);
                        }
                        break;
                    case 'sort_created':
                        if ($sortCreated = $requestEntity->getSortCreated()) {
                            $query->orderBy('created_at', $sortCreated);
                        }
                        break;
                    case 'sort_updated':
                        if ($sortUpdated = $requestEntity->getSortUpdated()) {
                            $query->orderBy('updated_at', $sortUpdated);
                        }
                        break;
                }
            }
        }

        return $query->paginate(
            $requestEntity->getLimit() ?? 10,
            ['*'],
            'page',
            $requestEntity->getPage() ?? 1
        );
    }

    public function getPermissionDetail(string $permissionId): ?PermissionDetailEntity
    {
        $permission = Permission::select([
            'permissions.id',
            'permissions.name',
            'permissions.display_name',
            'permissions.description',
            'permissions.usecase_group',
            'permissions.created_at',
            'permissions.updated_at',
        ])
            ->where('id', $permissionId)
            ->first();

        if (!$permission) {
            return null;
        }

        return new PermissionDetailEntity(
            id: $permission->id,
            name: $permission->name,
            displayName: $permission->display_name,
            description: $permission->description,
            usecaseGroup: $permission->usecase_group,
            createdAt: $permission->created_at,
            updatedAt: $permission->updated_at,
            statusCode: StatusCode::OK
        );
    }

    public function store(CreatePermissionRequestEntity $requestEntity): bool
    {
        try {
            $permission = new Permission;
            $permission->name = $requestEntity->name;
            $permission->display_name = $requestEntity->displayName;
            $permission->usecase_group = $requestEntity->usecaseGroup;
            if ($requestEntity->description) {
                $permission->description = $requestEntity->description;
            }
            $permission->save();
            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function update(UpdatePermissionRequestEntity $requestEntity, string $permissionId): bool
    {
        $permission = $this->findById($permissionId);
        if (!$permission) {
            return false;
        }

        DB::beginTransaction();
        try {

            if ($requestEntity->getDisplayName()) {
                $permission->display_name = $requestEntity->getDisplayName();
            }

            if ($requestEntity->getDescription()) {
                $permission->description = $requestEntity->getDescription();
            }

            if (!$permission->save()) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete(DeletePermissionRequestEntity $requestEntity): bool
    {
        $permission = $this->findById($requestEntity->getId());
        if (!$permission) {
            return false;
        }

        return $permission->delete();
    }

    private function findById(string $permissionId): ?Permission
    {
        return Permission::where('id', $permissionId)
            ->whereNull('deleted_at')
            ->first();
    }

    public function attachToUser(int $userId, array $permissionIds): bool
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($userId);
            $user->permissions()->syncWithoutDetaching($permissionIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function attachToRole(int $roleId, array $permissionIds): bool
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($roleId);
            $role->permissions()->syncWithoutDetaching($permissionIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function revokeToUser(int $userId, array $permissionIds): bool
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($userId);
            $user->permissions()->detach($permissionIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function revokeToRole(int $roleId, array $permissionIds): bool
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($roleId);
            $role->permissions()->detach($permissionIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function listPermissionFromUser(int $userId): array
    {
        $user = User::findOrFail($userId);

        // 直接付与された権限のIDを取得
        $directPermissionModels = $user->permissions()->get();
        $directPermissionIds = $directPermissionModels->pluck('id')->toArray();

        // 直接付与された権限を配列に変換
        $directPermissions = $directPermissionModels->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'display_name' => $permission->display_name,
                'description' => $permission->description,
                'usecase_group' => $permission->usecase_group,
                'source' => 'direct',
                'source_name' => null,
            ];
        });

        // Role経由の権限
        $rolePermissions = collect();
        $addedRolePermissionIds = [];

        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                // 直接付与された権限と重複しない場合のみ追加
                if (!\in_array($permission->id, $directPermissionIds, true) &&
                    !\in_array($permission->id, $addedRolePermissionIds, true)) {
                    $rolePermissions->push([
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'display_name' => $permission->display_name,
                        'description' => $permission->description,
                        'usecase_group' => $permission->usecase_group,
                        'source' => 'role',
                        'source_name' => $role->name,
                    ]);
                    $addedRolePermissionIds[] = $permission->id;
                }
            }
        }

        return array_merge($directPermissions->toArray(), $rolePermissions->toArray());
    }

    public function listPermissionFromRole(int $roleId): Collection
    {
        return Role::findOrFail($roleId)->permissions()->get();
    }

    public function getDistinctUsecaseGroups(): Collection
    {
        return Permission::selectRaw('MIN(id) as id, usecase_group, MIN(created_at) as created_at, MAX(updated_at) as updated_at')
            ->whereNotNull('usecase_group')
            ->groupBy('usecase_group')
            ->orderBy('usecase_group', 'ASC')
            ->get();
    }

    public function toggleActive(int $permissionId): ?array
    {
        $permission = Permission::find($permissionId);

        if (!$permission) {
            return null;
        }

        $permission->is_active = !$permission->is_active;
        $permission->save();

        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'display_name' => $permission->display_name,
            'is_active' => $permission->is_active,
        ];
    }
}
