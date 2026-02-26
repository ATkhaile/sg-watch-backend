<?php

namespace App\Domain\Banner\UseCase;

use App\Domain\Banner\Repository\BannerRepository;

final class GetPublicBannerListUseCase
{
    private BannerRepository $repository;

    public function __construct(BannerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): array
    {
        return $this->repository->getPublicList();
    }
}
