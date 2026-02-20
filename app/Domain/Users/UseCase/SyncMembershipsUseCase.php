<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Users\Repository\UserMembershipRepository;
use App\Domain\Users\Entity\SyncMembershipsRequestEntity;
use App\Domain\Memberships\Entity\StatusEntity;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class SyncMembershipsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'sync-user-memberships';
    public function __construct(
        private UserMembershipRepository $userMembershipRepository
    ) {}

    public function __invoke(SyncMembershipsRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        $userId = $requestEntity->getUserId();
        $membershipIds = $requestEntity->getMembershipIds();
        $entitlementRemovalAction = $requestEntity->getEntitlementRemovalAction();

        if ($this->userMembershipRepository->syncMemberships($userId, $membershipIds, $entitlementRemovalAction)) {
            return new StatusEntity(
                StatusCode::OK,
                'メンバーシップを更新しました'
            );
        }

        return new StatusEntity(
            StatusCode::INTERNAL_ERR,
            'メンバーシップの更新に失敗しました'
        );
    }
}
