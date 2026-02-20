<?php

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\Entity\UserEntity;
use App\Domain\Auth\Entity\EmailVerificationEntity;

interface UserRepository
{
    public function find(int $id): ?UserEntity;
    public function findByEmail(string $email): ?UserEntity;
    public function save(UserEntity $entity): bool;
    public function generateForgotPasswordToken(UserEntity $entity): ?UserEntity;
    public function checkToken(string $token): ?UserEntity;
    public function getUserByToken(string $token): ?UserEntity;
    public function changePassword(UserEntity $entity, string $newPassword): bool;
    public function updateCurrentPassword(UserEntity $entity, string $newPassword): bool;
    public function createEmailVerification(EmailVerificationEntity $entity): bool;
    public function findEmailVerification(string $token): ?EmailVerificationEntity;
    public function completeRegistration(EmailVerificationEntity $entity): ?UserEntity;
    public function findByInviteCode(string $inviteCode): ?UserEntity;
    public function updateProfile(int $userId, array $data): bool;
    public function updateAvatar(int $userId, string $avatarPath): bool;
    public function deleteAvatar(int $userId): bool;
    public function sessionLogin(int $userId): bool|string;
    public function getAppSettings(): array;

    // Password OTP
    public function invalidatePasswordOtps(string $email): void;
    public function createPasswordOtp(int $userId, string $email, string $code, int $expiresInSeconds): bool;
    public function findValidPasswordOtp(string $email): ?array;
    public function incrementOtpAttempts(int $otpId): void;
    public function markOtpAsUsed(int $otpId): void;
}
