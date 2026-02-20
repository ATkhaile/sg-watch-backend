<?php

namespace App\Domain\Chat\Entity;

class ChatPartnerEntity implements \JsonSerializable
{
    private ?array $partner = null;
    private int $statusCode = 200;
    private int $userId;
    private int $partnerId;

    public function __construct(
        int $userId,
        int $partnerId,
        ?array $partner = null,
        int $statusCode = 200
    ) {
        $this->userId = $userId;
        $this->partnerId = $partnerId;
        $this->partner = $partner;
        $this->statusCode = $statusCode;
    }

    public function jsonSerialize(): array
    {
        return [
            'partner' => $this->partner,
            'status_code' => $this->statusCode
        ];
    }

    public function getPartner(): ?array
    {
        return $this->partner;
    }

    public function setPartner(?array $partner): self
    {
        $this->partner = $partner;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function setStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPartnerId(): int
    {
        return $this->partnerId;
    }
}
