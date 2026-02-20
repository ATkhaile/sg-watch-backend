<?php

namespace App\Domain\Auth\Entity;

class UpdateProfileRequestEntity
{
    public function __construct(
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $gender = null,
        private ?string $birthday = null,
        private ?string $avatarUrl = null,
        private bool $hasFirstName = false,
        private bool $hasLastName = false,
        private bool $hasGender = false,
        private bool $hasBirthday = false,
        private bool $hasAvatarUrl = false,
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function hasFirstName(): bool
    {
        return $this->hasFirstName;
    }

    public function hasLastName(): bool
    {
        return $this->hasLastName;
    }

    public function hasGender(): bool
    {
        return $this->hasGender;
    }

    public function hasBirthday(): bool
    {
        return $this->hasBirthday;
    }

    public function hasAvatarUrl(): bool
    {
        return $this->hasAvatarUrl;
    }
}
