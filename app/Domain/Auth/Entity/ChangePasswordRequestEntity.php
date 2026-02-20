<?php

namespace App\Domain\Auth\Entity;

final class ChangePasswordRequestEntity
{
    public function __construct(
        private string $oldPassword,
        private string $newPassword
    ) {
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
