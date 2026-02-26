<?php

namespace App\Domain\Banner\UseCase;

use App\Domain\Banner\Repository\BannerRepository;

final class GetBannerListUseCase
{
    private BannerRepository $repository;

    public function __construct(BannerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
