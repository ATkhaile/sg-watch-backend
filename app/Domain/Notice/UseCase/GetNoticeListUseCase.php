<?php

namespace App\Domain\Notice\UseCase;

use App\Domain\Notice\Repository\NoticeRepository;

final class GetNoticeListUseCase
{
    private NoticeRepository $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
