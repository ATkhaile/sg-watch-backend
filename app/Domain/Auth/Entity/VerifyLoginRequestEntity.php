<?php

namespace App\Domain\Auth\Entity;

class VerifyLoginRequestEntity
{
    private function __construct(
        private ?string $email,
        private ?string $userId,
        private string $verificationCode,
    ) {
    }

    public static function createFromEmail(string $email, string $verificationCode): self
    {
        return new self(
            email: $email,
            userId: null,
            verificationCode: $verificationCode
        );
    }

    public static function createFromUserId(string $userId, string $verificationCode): self
    {
        return new self(
            email: null,
            userId: $userId,
            verificationCode: $verificationCode
        );
    }

    public function getCredentials(): array
    {
        if ($this->email !== null) {
            return [
                'email' => $this->email,
            ];
        }

        return [
            'user_id' => $this->userId,
        ];
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }
}
