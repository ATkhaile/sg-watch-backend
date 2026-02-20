<?php

namespace App\Domain\Auth\Entity;

class UpdateCurrentPasswordRequestEntity
{
    private function __construct(
        private string $email,
        private string $oldPassword,
        private string $newPassword
    ) {
    }

    public static function create(
        string $email,
        string $oldPassword,
        string $newPassword
    ): self {
        return new self(
            email: $email,
            oldPassword: $oldPassword,
            newPassword: $newPassword
        );
    }

    public function getEmail(): string
    {
        return $this->email;
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
