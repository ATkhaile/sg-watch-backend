<?php

namespace App\Domain\Auth\Entity;

class UserInfoEntity implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private ?string $avatarUrl = null,
        private ?string $gender = null,
        private ?string $birthday = null,
        private ?string $role = null,
        private ?string $email = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        $result['first_name'] = $this->firstName;
        $result['last_name'] = $this->lastName;

        if ($this->avatarUrl !== null) {
            $result['avatar_url'] = $this->avatarUrl;
        }

        $result['gender'] = $this->gender;
        $result['birthday'] = $this->birthday;
        $result['role'] = $this->role;
        $result['email'] = $this->email;

        return $result;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
