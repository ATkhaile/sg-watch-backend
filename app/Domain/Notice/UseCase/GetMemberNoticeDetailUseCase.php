<?php

namespace App\Domain\Notice\UseCase;

use App\Domain\Notice\Repository\NoticeRepository;

final class GetMemberNoticeDetailUseCase
{
    private NoticeRepository $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, string $noticeId): ?array
    {
        return $this->repository->getMemberNoticeDetail($userId, $noticeId);
    }
}
