<?php

namespace App\Domain\Users\Infrastructure;

use App\Domain\Users\Repository\UserMembershipRepository;
use App\Domain\Users\Entity\EntitlementRemovalAction;
use App\Models\User;
use Illuminate\Support\Collection;

class DbUserMembershipInfrastructure implements UserMembershipRepository
{
    public function syncMemberships(int $userId, array $membershipIds, string $entitlementRemovalAction = EntitlementRemovalAction::KEEP_ENABLED): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        try {
            // 既存メンバーシップを取得（後でエンタイトルメント処理用）
            $existingMembershipIds = $user->memberships()->pluck('memberships.id')->toArray();

            // Prepare pivot data with granted_at for all memberships
            $syncData = [];
            foreach ($membershipIds as $membershipId) {
                $syncData[$membershipId] = ['granted_at' => now()];
            }
            $user->memberships()->sync($syncData);

            // エンタイトルメントを処理
            $entitlementService = app(\App\Domain\Entitlement\Service\EntitlementService::class);

            // 削除されたメンバーシップのエンタイトルメントを処理
            $removedMembershipIds = array_diff($existingMembershipIds, $membershipIds);
            foreach ($removedMembershipIds as $removedMembershipId) {
                switch ($entitlementRemovalAction) {
                    case EntitlementRemovalAction::REVOKE:
                        // エンタイトルメントを削除
                        $entitlementService->revokeFromMembership($user, $removedMembershipId);
                        break;
                    case EntitlementRemovalAction::DISABLE:
                        // エンタイトルメントを無効化（削除せずに残す）
                        $entitlementService->disableFromMembership($user, $removedMembershipId);
                        break;
                    case EntitlementRemovalAction::KEEP_ENABLED:
                    default:
                        // エンタイトルメントを有効のまま残す（何もしない）
                        break;
                }
            }

            // 新規追加または既存メンバーシップのエンタイトルメントを付与（上書き）
            foreach ($membershipIds as $membershipId) {
                $entitlementService->grantFromMembership($user, $membershipId);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to sync memberships for user: ' . $e->getMessage());
            return false;
        }
    }

    public function getMemberships(int $userId): Collection
    {
        $user = User::find($userId);
        if (!$user) {
            return collect([]);
        }

        return $user->memberships->map(function ($membership) {
            return [
                'id' => $membership->id,
                'name' => $membership->name,
                'description' => $membership->description,
                'granted_at' => $membership->pivot->granted_at,
                'expires_at' => $membership->pivot->expires_at,
                'granted_by' => $membership->pivot->granted_by,
            ];
        });
    }
}
