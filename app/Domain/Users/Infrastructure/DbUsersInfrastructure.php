<?php

namespace App\Domain\Users\Infrastructure;

use App\Domain\Users\Entity\UsersEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Domain\Users\Entity\UsersDetailEntity;
use App\Domain\Users\Entity\CreateUsersRequestEntity;
use App\Domain\Users\Entity\UpdateUsersRequestEntity;
use App\Domain\Users\Entity\DeleteUsersRequestEntity;
use App\Domain\Users\Entity\GetUsersWithStoriesRequestEntity;
use App\Domain\Users\Entity\UserSessionDevicesEntity;
use App\Models\User;
use App\Enums\StatusCode;
use App\Components\CommonComponent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\{Hash, DB};

class DbUsersInfrastructure implements UsersRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllUsers(UsersEntity $requestEntity): LengthAwarePaginator
    {
        $query = User::select(['id', 'first_name', 'last_name', 'avatar_url']);

        if (auth()->check()) {
            $query->addSelect([
                'is_following' => \App\Models\UserFollow::selectRaw('count(*)')
                    ->whereColumn('following_id', 'users.id')
                    ->where('follower_id', auth()->id())
            ]);
        } else {
            $query->addSelect(DB::raw('0 as is_following'));
        }

        #Search
        if ($search = $requestEntity->getSearch()) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($searchEmail = $requestEntity->getSearchEmail()) {
            $operatorEmail = $requestEntity->getSearchEmailLike() ? 'LIKE' : '=';
            $searchValueEmail = $requestEntity->getSearchEmailLike() ? "%{$searchEmail}%" : $searchEmail;

            if ($requestEntity->getSearchEmailNot()) {
                $query->where('email', '!=', $searchEmail);
            } else {
                $query->where('email', $operatorEmail, $searchValueEmail);
            }
        }

        // Filter by admin role
        if ($requestEntity->getAdmin() !== null) {
            $query->where('is_system_admin', $requestEntity->getAdmin());
        }

        #Sort
        $sortOrder = $requestEntity->getSortOrder();
        if ($sortOrder && !empty($sortOrder)) {
            foreach ($sortOrder as $param) {
                switch ($param) {
                    case 'sort_first_name':
                        if ($sortFirstName = $requestEntity->getSortFirstName()) {
                            $query->orderBy('first_name', $sortFirstName);
                        }
                        break;
                    case 'sort_email':
                        if ($sortEmail = $requestEntity->getSortEmail()) {
                            $query->orderBy('email', $sortEmail);
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

    public function getUsersDetail(string $userId): ?UsersDetailEntity
    {
        $user = User::select([
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.gender',
            'users.avatar_url',
            'users.is_system_admin',
            'users.invite_code',
            'users.created_at',
            'users.updated_at',
        ])
            ->where('id', $userId)
            ->first();

        if (!$user) {
            return null;
        }

        $avatarUrl = CommonComponent::getFullUrl($user->avatar_url);

        return new UsersDetailEntity(
            id: $user->id,
            firstName: $user->first_name,
            lastName: $user->last_name,
            email: $user->email,
            gender: $user->gender,
            avatarUrl: $avatarUrl,
            isSystemAdmin: (bool) $user->is_system_admin,
            inviteCode: $user->invite_code,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            statusCode: StatusCode::OK
        );
    }

    public function store(CreateUsersRequestEntity $requestEntity): bool
    {
        try {
            $user = new User;
            $user->first_name = $requestEntity->first_name;
            $user->last_name = $requestEntity->last_name;
            $user->email = $requestEntity->email;
            $user->password = Hash::make($requestEntity->password);
            $user->is_system_admin = true;
            $user->save();

            $adminRole = \App\Models\Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(UpdateUsersRequestEntity $requestEntity, string $userId): bool
    {
        $user = $this->findById($userId);
        if (!$user) {
            return false;
        }

        DB::beginTransaction();
        try {
            if ($requestEntity->getFirstName() !== null) {
                $user->first_name = $requestEntity->getFirstName();
            }

            if ($requestEntity->getLastName() !== null) {
                $user->last_name = $requestEntity->getLastName();
            }

            if ($requestEntity->getEmail()) {
                $user->email = $requestEntity->getEmail();
            }

            if ($requestEntity->getGender() !== null) {
                $user->gender = $requestEntity->getGender();
            }

            if ($requestEntity->getPassword()) {
                $user->password = Hash::make($requestEntity->getPassword());
            }

            if (!$user->save()) {
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

    public function delete(DeleteUsersRequestEntity $requestEntity): bool
    {
        $user = $this->findById($requestEntity->getId());
        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    private function findById(string $userId): ?User
    {
        return User::where('id', $userId)
            ->whereNull('deleted_at')
            ->first();
    }

    public function getUsersWithStories(GetUsersWithStoriesRequestEntity $entity): LengthAwarePaginator
    {
        $query = User::query()
            ->whereHas('storiesFlag')
            ->withCount('storiesFlag as stories_flag_count');

        return $query->paginate(
            $entity->getLimit() ?? 10,
            ['*'],
            'page',
            $entity->getPage() ?? 1
        );
    }

    public function getUserSessionDevices(UserSessionDevicesEntity $entity): LengthAwarePaginator
    {
        $query = DB::table('sessions')
            ->where('user_id', $entity->user_id);

        if ($entity->status) {
            $query->where('is_active', $entity->status === 'active');
        }

        $sortBy = $entity->sortBy ?? 'id';
        $sortDirection = strtoupper($entity->sortDirection ?? 'DESC');
        $sortDirection = in_array($sortDirection, ['ASC', 'DESC'], true)
            ? $sortDirection
            : 'DESC';

        $allowedSort = ['id', 'last_activity', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate(
            $entity->limit ?? 10,
            ['*'],
            'page',
            $entity->page ?? 1
        );
    }

    public function deleteSessionDevice(int $sessionId): bool
    {
        return DB::table('sessions')
            ->where('id', $sessionId)
            ->delete();
    }
}
