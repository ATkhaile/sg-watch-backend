<?php

namespace App\Domain\Notice\UseCase;

use App\Domain\Notice\Repository\NoticeRepository;

final class MarkNoticeAsReadUseCase
{
    private NoticeRepository $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $userNoticeId): array
    {
        return $this->repository->markAsRead($userId, $userNoticeId);
    }
}
