<?php

namespace App\Domain\Notice\UseCase;

use App\Domain\Notice\Repository\NoticeRepository;

final class CreateNoticeUseCase
{
    private NoticeRepository $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
