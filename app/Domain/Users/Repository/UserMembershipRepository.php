<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\EntitlementRemovalAction;
use Illuminate\Support\Collection;

interface UserMembershipRepository
{
    public function syncMemberships(int $userId, array $membershipIds, string $entitlementRemovalAction = EntitlementRemovalAction::KEEP_ENABLED): bool;
    public function getMemberships(int $userId): Collection;
}
