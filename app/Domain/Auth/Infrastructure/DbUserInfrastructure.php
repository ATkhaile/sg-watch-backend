<?php

namespace App\Domain\Auth\Infrastructure;

use App\Components\PointBonusComponent;
use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\{UserEntity, EmailVerificationEntity};
use App\Models\{User, EmailVerification, EmailVerificationCode};
use Carbon\Carbon;
use Illuminate\Support\Facades\{Hash, DB};
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use App\Domain\Sessions\Entity\CreateSessionRequestEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use App\Models\AppSetting;
use App\Enums\AppSettingType;

class DbUserInfrastructure extends BaseController implements UserRepository
{
    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function find(int $id): ?UserEntity
    {
        $user = User::find($id);
        return $user ? $this->toEntity($user) : null;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        try {
            $user = User::where('email', $email)
                ->whereNull('deleted_at')
                ->first();

            return $user ? $this->toEntity($user) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function save(UserEntity $entity): bool
    {
        DB::beginTransaction();
        try {
            $user = new User;
            $user->fill([
                'first_name'   => $entity->getFirstName(),
                'last_name'    => $entity->getLastName(),
                'email'        => $entity->getEmail(),
                'password'     => Hash::make($entity->getPassword()),
                'is_system_admin' => false,
                'invite_code'  => $this->generateUniqueInviteCode(),
            ]);

            $inviter = null;
            $inviteCodeByOtherUser = $entity->getInviteCode();
            if ($inviteCodeByOtherUser) {
                $inviter = User::where('invite_code', $inviteCodeByOtherUser)->first();
                if ($inviter) {
                    $user->inviter_id = $inviter->id;
                    $user->invited_at = now();
                }
            }

            if ($user->save()) {
                $userRole = \App\Models\Role::where('name', 'user')->first();
                if ($userRole) {
                    $user->roles()->syncWithoutDetaching([$userRole->id]);
                }

                if ($inviter) {
                    PointBonusComponent::affiliateBonus($inviter->id, $user->id);
                }
                DB::commit();
                return true;
            }
            throw new \Exception('User save failed');
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function generateForgotPasswordToken(UserEntity $entity): ?UserEntity
    {
        try {
            DB::beginTransaction();

            $user = User::where('email', $entity->getEmail())->first();
            if (!$user) {
                DB::rollBack();
                return null;
            }

            $token = Str::random(64);

            $user->reset_password_token = $token;
            $user->reset_password_token_expire = now()->addMinutes(15);

            if (!$user->save()) {
                DB::rollBack();
                return null;
            }

            DB::commit();

            return $this->toEntity($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function checkToken(string $token): ?UserEntity
    {
        $user = User::where('reset_password_token', $token)
            ->where('reset_password_token_expire', '>', Carbon::now())
            ->first();

        return $user ? $this->toEntity($user) : null;
    }

    public function getUserByToken(string $token): ?UserEntity
    {
        $user = User::where('reset_password_token', $token)
            ->where('reset_password_token_expire', '>', Carbon::now())
            ->first();

        return $user ? $this->toEntity($user) : null;
    }

    public function changePassword(UserEntity $entity, string $newPassword): bool
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', $entity->getId())->first();

            if (!$user) {
                DB::rollBack();
                return false;
            }

            $user->password = Hash::make($newPassword);
            $user->reset_password_token = null;
            $user->reset_password_token_expire = null;

            $result = $user->save();

            if (!$result) {
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

    public function updateCurrentPassword(UserEntity $entity, string $newPassword): bool
    {
        try {
            DB::beginTransaction();

            $user = User::where('id', $entity->getId())->first();
            if (!$user) {
                DB::rollBack();
                return false;
            }

            $user->password = Hash::make($newPassword);
            $result = $user->save();

            if (!$result) {
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

    private function toEntity(User $model): UserEntity
    {
        return new UserEntity(
            id: $model->id,
            email: $model->email,
            firstName: $model->first_name,
            lastName: $model->last_name,
            isSystemAdmin: $model->isSystemAdmin(),
            avatarUrl: $model->avatar_url,
            resetPasswordToken: $model->reset_password_token,
            resetPasswordTokenExpire: $model->reset_password_token_expire,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }

    public function createEmailVerification(EmailVerificationEntity $entity): bool
    {
        try {
            return DB::table('email_verifications')->insert([
                'email' => $entity->getEmail(),
                'first_name' => $entity->getFirstName(),
                'last_name' => $entity->getLastName(),
                'inviter_id' => $entity->getInviterId(),
                'password' => Hash::make($entity->getPassword()),
                'token' => $entity->getToken(),
                'expires_at' => $entity->getExpiresAt(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findEmailVerification(string $token): ?EmailVerificationEntity
    {
        $model = EmailVerification::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$model) {
            return null;
        }

        return new EmailVerificationEntity(
            email: $model->email,
            firstName: $model->first_name,
            lastName: $model->last_name,
            password: $model->password,
            token: $model->token,
            expiresAt: $model->expires_at,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
            inviterId: $model->inviter_id
        );
    }

    public function completeRegistration(EmailVerificationEntity $entity): ?UserEntity
    {
        try {
            DB::beginTransaction();

            $user = new User;
            $inviterId = $entity->getInviterId();
            $user->fill([
                'first_name' => $entity->getFirstName(),
                'last_name' => $entity->getLastName(),
                'email' => $entity->getEmail(),
                'password' => $entity->getPassword(),
                'invite_code' => $this->generateUniqueInviteCode(),
            ]);
            if ($inviterId) {
                $user->inviter_id = $inviterId;
                $user->invited_at = now();
            }

            $result = $user->save();

            if ($result) {
                EmailVerification::where('email', $entity->getEmail())->delete();

                // Assign user role
                $userRole = \App\Models\Role::where('name', 'user')->first();
                if ($userRole) {
                    $user->roles()->syncWithoutDetaching([$userRole->id]);
                }

                if ($inviterId) {
                    PointBonusComponent::affiliateBonus($inviterId, $user->id);
                }

                $userEntity = $this->toEntity($user);
                DB::commit();
                return $userEntity;
            }

            DB::rollBack();
            return null;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function findByInviteCode(string $inviteCode): ?UserEntity
    {
        $user = User::where('invite_code', $inviteCode)->first();
        return $user ? $this->toEntity($user) : null;
    }

    public function updateProfile(int $userId, array $data): bool
    {
        try {
            DB::beginTransaction();

            $user = User::find($userId);
            if (!$user) {
                DB::rollBack();
                return false;
            }

            $user->fill($data);
            $result = $user->save();

            if (!$result) {
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

    public function updateAvatar(int $userId, string $avatarPath): bool
    {
        try {
            DB::beginTransaction();

            $user = User::find($userId);
            if (!$user) {
                DB::rollBack();
                return false;
            }

            $user->avatar_url = $avatarPath;
            $result = $user->save();

            if (!$result) {
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

    public function deleteAvatar(int $userId): bool
    {
        try {
            DB::beginTransaction();

            $user = User::find($userId);
            if (!$user) {
                DB::rollBack();
                return false;
            }

            $user->avatar_url = null;
            $result = $user->save();

            if (!$result) {
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

    public function sessionLogin(int $userId): bool|string
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $token = auth()->login($user);
        if (!$token) {
            return false;
        }
        PointBonusComponent::dailyBonus();

        $sessionRequest = new CreateSessionRequestEntity(
            userId: $user->id,
            token: $token,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
            appId: request()->header('X-App-Id'),
            domain: request()->header('Origin')
        );
        if (!$this->sessionRepository->store($sessionRequest)) {
            return false;
        }
        return $token;
    }

    public function getAppSettings(): array
    {
        $currentUserId = auth()->id();

        $appSettings = AppSetting::where('type', AppSettingType::FOOTER)
            ->where('public_flag', 1)
            ->where(function ($q) use ($currentUserId) {
                $q->whereNull('user_id')
                    ->orWhere('user_id', $currentUserId);
            })
            ->orderBy('order_num', 'ASC')
            ->get();
        if ($appSettings->where('user_id', $currentUserId)->isNotEmpty()) {
            $appSettings = $appSettings->where('user_id', $currentUserId);
        } else {
            $appSettings = $appSettings->whereNull('user_id');
        }

        return $appSettings->pluck('link')->toArray();
    }

    // Password OTP methods

    public function invalidatePasswordOtps(string $email): void
    {
        EmailVerificationCode::where('email', $email)
            ->where('type', 'password_reset')
            ->where('is_used', false)
            ->update(['is_used' => true]);
    }

    public function createPasswordOtp(int $userId, string $email, string $code, int $expiresInSeconds): bool
    {
        try {
            EmailVerificationCode::create([
                'user_id' => $userId,
                'email' => $email,
                'code' => $code,
                'type' => 'password_reset',
                'is_used' => false,
                'attempts' => 0,
                'expires_at' => Carbon::now()->addSeconds($expiresInSeconds),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findValidPasswordOtp(string $email): ?array
    {
        $record = EmailVerificationCode::where('email', $email)
            ->where('type', 'password_reset')
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
            return null;
        }

        return [
            'id' => $record->id,
            'code' => $record->code,
            'attempts' => $record->attempts,
        ];
    }

    public function incrementOtpAttempts(int $otpId): void
    {
        EmailVerificationCode::where('id', $otpId)->increment('attempts');
    }

    public function markOtpAsUsed(int $otpId): void
    {
        EmailVerificationCode::where('id', $otpId)->update(['is_used' => true]);
    }
}
