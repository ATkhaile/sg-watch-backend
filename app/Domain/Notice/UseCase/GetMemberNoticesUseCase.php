<?php

namespace App\Domain\Notice\UseCase;

use App\Domain\Notice\Repository\NoticeRepository;

final class GetMemberNoticesUseCase
{
    private NoticeRepository $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, array $filters): array
    {
        return $this->repository->getMemberNotices($userId, $filters);
    }
}
