<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\SyncMembershipsRequestEntity;
use App\Domain\Users\Entity\EntitlementRemovalAction;
use Illuminate\Http\Request;

class SyncMembershipsRequestFactory
{
    public function createFromRequest(Request $request, int $userId): SyncMembershipsRequestEntity
    {
        $entitlementAction = $request->input('entitlement_removal_action', EntitlementRemovalAction::KEEP_ENABLED);

        // Validate the action
        $validActions = [
            EntitlementRemovalAction::KEEP_ENABLED,
            EntitlementRemovalAction::DISABLE,
            EntitlementRemovalAction::REVOKE,
        ];
        if (!in_array($entitlementAction, $validActions, true)) {
            $entitlementAction = EntitlementRemovalAction::KEEP_ENABLED;
        }

        return new SyncMembershipsRequestEntity(
            userId: $userId,
            membershipIds: $request->input('membership_ids', []),
            entitlementRemovalAction: $entitlementAction
        );
    }
}
