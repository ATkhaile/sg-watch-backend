<?php

namespace App\Domain\Auth\Entity;

class VerifyPasswordOtpRequestEntity
{
    private function __construct(
        private string $email,
        private string $otp
    ) {
    }

    public static function create(string $email, string $otp): self
    {
        return new self(email: $email, otp: $otp);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getOtp(): string
    {
        return $this->otp;
    }
}
