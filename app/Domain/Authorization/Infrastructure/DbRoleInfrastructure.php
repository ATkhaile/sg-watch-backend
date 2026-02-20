<?php

namespace App\Domain\Authorization\Infrastructure;

use App\Domain\Authorization\Entity\RolesEntity;
use App\Domain\Authorization\Repository\RoleRepository;
use App\Domain\Authorization\Entity\RoleDetailEntity;
use App\Domain\Authorization\Entity\CreateRoleRequestEntity;
use App\Domain\Authorization\Entity\UpdateRoleRequestEntity;
use App\Domain\Authorization\Entity\DeleteRoleRequestEntity;
use App\Domain\Authorization\Entity\ListRoleFromUserRequestEntity;
use App\Models\Role;
use App\Models\User;
use App\Enums\StatusCode;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class DbRoleInfrastructure implements RoleRepository
{
    private Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function getAllRole(RolesEntity $requestEntity): LengthAwarePaginator
    {
        $query = Role::query()->withCount('users');

        #Search
        if ($searchName = $requestEntity->getSearchName()) {
            $operatorName = $requestEntity->getSearchNameLike() ? 'LIKE' : '=';
            $searchValueName = $requestEntity->getSearchNameLike() ? "%{$searchName}%" : $searchName;

            if ($requestEntity->getSearchNameNot()) {
                $query->where('name', '!=', $searchName);
            } else {
                $query->where('name', $operatorName, $searchValueName);
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

    public function getRoleDetail(string $roleId): ?RoleDetailEntity
    {
        $role = Role::select([
            'roles.id',
            'roles.name',
            'roles.display_name',
            'roles.description',
            'roles.created_at',
            'roles.updated_at',
        ])
            ->where('id', $roleId)
            ->first();

        if (!$role) {
            return null;
        }

        return new RoleDetailEntity(
            id: $role->id,
            name: $role->name,
            displayName: $role->display_name,
            description: $role->description,
            createdAt: $role->created_at,
            updatedAt: $role->updated_at,
            statusCode: StatusCode::OK
        );
    }

    public function store(CreateRoleRequestEntity $requestEntity): bool
    {
        try {
            $role = new Role;
            $role->name = $requestEntity->name;
            $role->display_name = $requestEntity->displayName;
            if ($requestEntity->description) {
                $role->description = $requestEntity->description;
            }
            $role->save();
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }

    public function update(UpdateRoleRequestEntity $requestEntity, string $roleId): bool
    {
        $role = $this->findById($roleId);
        if (!$role) {
            return false;
        }

        DB::beginTransaction();
        try {
            if ($requestEntity->getName()) {
                $role->name = $requestEntity->getName();
            }

            if ($requestEntity->getDisplayName()) {
                $role->display_name = $requestEntity->getDisplayName();
            }

            if ($requestEntity->getDescription()) {
                $role->description = $requestEntity->getDescription();
            }

            if (!$role->save()) {
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

    public function delete(DeleteRoleRequestEntity $requestEntity): bool
    {
        $role = $this->findById($requestEntity->getId());
        if (!$role) {
            return false;
        }

        return $role->delete();
    }

    private function findById(string $roleId): ?Role
    {
        return Role::where('id', $roleId)
            ->whereNull('deleted_at')
            ->first();
    }

    public function attachToUser(int $userId, array $roleIds): bool
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($userId);
            $user->roles()->syncWithoutDetaching($roleIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function revokeToUser(int $userId, array $roleIds): bool
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($userId);
            $user->roles()->detach($roleIds);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function listRoleFromUser(ListRoleFromUserRequestEntity $requestEntity): Collection
    {
        $user = User::findOrFail($requestEntity->getUserId());
        return $user->roles()->get();
    }
}
