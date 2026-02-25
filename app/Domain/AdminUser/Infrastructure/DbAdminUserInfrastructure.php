<?php

namespace App\Domain\AdminUser\Infrastructure;

use App\Domain\AdminUser\Repository\AdminUserRepository;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DbAdminUserInfrastructure implements AdminUserRepository
{
    public function getList(array $filters): array
    {
        $query = User::query()->with(['roles:id,name,display_name']);

        // Search by name/email
        if (!empty($filters['keyword'])) {
            $keyword = '%' . $filters['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', $keyword)
                  ->orWhere('last_name', 'like', $keyword)
                  ->orWhere('email', 'like', $keyword)
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$keyword]);
            });
        }

        // Filter by gender
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // Filter by is_system_admin
        if (isset($filters['is_system_admin'])) {
            $query->where('is_system_admin', $filters['is_system_admin']);
        }

        // Filter by role
        if (!empty($filters['role'])) {
            $query->whereHas('roles', fn($q) => $q->where('name', $filters['role']));
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at_desc';
        match ($sortBy) {
            'created_at_asc' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc'),
            'name_desc' => $query->orderBy('first_name', 'desc')->orderBy('last_name', 'desc'),
            'email_asc' => $query->orderBy('email', 'asc'),
            'email_desc' => $query->orderBy('email', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = $filters['per_page'] ?? 15;
        $paginator = $query->paginate($perPage);

        return [
            'users' => collect($paginator->items())->map(fn($user) => $this->formatUserSummary($user))->toArray(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    public function getById(int $id): ?array
    {
        $user = User::with(['roles:id,name,display_name'])->find($id);

        if (!$user) {
            return null;
        }

        return $this->formatUserDetail($user);
    }

    public function create(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $roleIds = $data['role_ids'] ?? [];
            unset($data['role_ids']);

            $avatar = $data['avatar'] ?? null;
            unset($data['avatar']);

            $data['invite_code'] = $this->generateUniqueInviteCode();

            $user = User::create($data);

            // Upload avatar
            if ($avatar instanceof UploadedFile) {
                $path = $avatar->store('avatars/' . $user->id, 'public');
                $user->update(['avatar_url' => $path]);
            }

            if (!empty($roleIds)) {
                $user->roles()->sync($roleIds);
            }

            $user->load(['roles:id,name,display_name']);

            return [
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $this->formatUserDetail($user),
            ];
        });
    }

    public function update(int $id, array $data): array
    {
        $user = User::find($id);

        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        return DB::transaction(function () use ($user, $data) {
            $roleIds = $data['role_ids'] ?? null;
            unset($data['role_ids']);

            $avatar = $data['avatar'] ?? null;
            unset($data['avatar']);

            // Only update password if provided and non-empty
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user->update($data);

            // Upload avatar
            if ($avatar instanceof UploadedFile) {
                if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $path = $avatar->store('avatars/' . $user->id, 'public');
                $user->update(['avatar_url' => $path]);
            }

            // Sync roles if provided
            if ($roleIds !== null) {
                $user->roles()->sync($roleIds);
            }

            $user->load(['roles:id,name,display_name']);

            return [
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $this->formatUserDetail($user),
            ];
        });
    }

    public function delete(int $id, int $currentUserId): array
    {
        if ($id === $currentUserId) {
            return [
                'success' => false,
                'message' => 'You cannot delete your own account.',
                'status_code' => 403,
            ];
        }

        $user = User::find($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.',
                'status_code' => 404,
            ];
        }

        $user->delete();

        return [
            'success' => true,
            'message' => 'User deleted successfully.',
            'status_code' => 200,
        ];
    }

    private function generateUniqueInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(6)) . '-' . Str::upper(Str::random(6));
        } while (DB::table('users')->where('invite_code', $code)->exists());

        return $code;
    }

    private function formatUserSummary(User $user): array
    {
        return [
            'id' => $user->id,
            'uuid' => $user->uuid,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'gender' => $user->gender,
            'avatar_url' => $user->avatar_full_url,
            'is_system_admin' => $user->is_system_admin,
            'roles' => $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
            ])->toArray(),
            'created_at' => $user->created_at?->toIso8601String(),
        ];
    }

    private function formatUserDetail(User $user): array
    {
        return [
            'id' => $user->id,
            'uuid' => $user->uuid,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'gender' => $user->gender,
            'avatar_url' => $user->avatar_full_url,
            'birthday' => $user->birthday?->format('Y-m-d'),
            'is_system_admin' => $user->is_system_admin,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'invite_code' => $user->invite_code,
            'inviter_id' => $user->inviter_id,
            'invited_at' => $user->invited_at?->toIso8601String(),
            'roles' => $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
            ])->toArray(),
            'created_at' => $user->created_at?->toIso8601String(),
            'updated_at' => $user->updated_at?->toIso8601String(),
        ];
    }
}
