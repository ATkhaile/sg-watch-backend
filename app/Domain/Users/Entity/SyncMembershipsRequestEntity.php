<?php

namespace App\Domain\Users\Entity;

final class SyncMembershipsRequestEntity
{
    public function __construct(
        private int $userId,
        private array $membershipIds,
        private string $entitlementRemovalAction = EntitlementRemovalAction::KEEP_ENABLED
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMembershipIds(): array
    {
        return $this->membershipIds;
    }

    public function getEntitlementRemovalAction(): string
    {
        return $this->entitlementRemovalAction;
    }
}
