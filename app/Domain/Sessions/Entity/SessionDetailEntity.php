<?php

namespace App\Domain\Sessions\Entity;

use DateTime;

class SessionDetailEntity
{
    public function __construct(
        private ?int $id = null,
        private ?int $userId = null,
        private ?string $token = null,
        private ?string $tokenHash = null,
        private ?string $ipAddress = null,
        private ?string $userAgent = null,
        private ?DateTime $lastActivity = null,
        private ?bool $isActive = null,
        private int $statusCode = 200
    ) {
        if ($token) {
            $this->tokenHash = hash('sha256', $token);
        }
        if (!$lastActivity) {
            $this->lastActivity = new DateTime;
        }
    }

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->userId !== null) {
            $result['user_id'] = $this->userId;
        }

        if ($this->token !== null) {
            $result['token'] = $this->token;
        }

        if ($this->ipAddress !== null) {
            $result['ip_address'] = $this->ipAddress;
        }

        if ($this->userAgent !== null) {
            $result['user_agent'] = $this->userAgent;
        }

        if ($this->lastActivity !== null) {
            $result['last_activity'] = $this->lastActivity->format('Y-m-d H:i:s');
        }

        if ($this->isActive !== null) {
            $result['is_active'] = $this->isActive;
        }

        return $result;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getTokenHash(): ?string
    {
        return $this->tokenHash;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getLastActivity(): ?DateTime
    {
        return $this->lastActivity;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        if ($token) {
            $this->tokenHash = hash('sha256', $token);
        }
        return $this;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function setLastActivity(?DateTime $lastActivity): self
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    public function setActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
