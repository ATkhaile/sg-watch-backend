<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Users\Repository\UserMembershipRepository;
use Illuminate\Support\Collection;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetUserMembershipsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-user-memberships';
    public function __construct(
        private UserMembershipRepository $userMembershipRepository
    ) {}

    public function __invoke(int $userId): Collection
    {
        $this->authorize();

        return $this->userMembershipRepository->getMemberships($userId);
    }
}
