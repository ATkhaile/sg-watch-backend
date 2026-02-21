<?php

namespace App\Domain\Sessions\Entity;

class CreateSessionRequestEntity
{
    private int $userId;
    private string $token;
    private ?string $ipAddress;
    private ?string $userAgent;
    private ?string $appId;
    private ?string $domain;

    public function __construct(
        int $userId,
        string $token,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $appId = null,
        ?string $domain = null,
    ) {
        $this->userId = $userId;
        $this->token = $token;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->appId = $appId;
        $this->domain = $domain;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
