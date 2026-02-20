<?php

namespace App\Domain\UserProfile\Infrastructure;

use App\Domain\UserProfile\Entity\UpdateUserProfileRequestEntity;
use App\Domain\UserProfile\Repository\UserProfileRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DbUserProfileInfrastructure implements UserProfileRepository
{
    public function getUser(int $userId): ?User
    {
        return User::select(
            'id',
            'line_name',
            'last_name_kanji',
            'first_name_kanji',
            'last_name_kana',
            'first_name_kana',
            'group_name',
            'group_name_kana',
            'email',
            'email_send',
            'password',
            'birthday',
            'postal_code',
            'prefecture_id',
            'city',
            'street_address',
            'building',
            'phone',
            'is_black',
            'billing_same_address_flag',
            'memo'
        )
        ->find($userId);
    }

    public function update(int $userId, UpdateUserProfileRequestEntity $entity): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        // Fill the entity data directly (column names match), excluding password
        $data = $entity->toArray();
        unset($data['password']);
        $user->fill($data);

        // Only update password if provided
        if ($entity->password) {
            $user->password = Hash::make($entity->password);
        }

        return $user->save();
    }
}
