<?php

namespace App\Domain\Notice\UseCase;

use App\Domain\Notice\Repository\NoticeRepository;

final class GetNoticeDetailUseCase
{
    private NoticeRepository $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->getById($id);
    }
}
