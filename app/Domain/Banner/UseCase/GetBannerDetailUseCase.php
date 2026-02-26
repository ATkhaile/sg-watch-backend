<?php

namespace App\Domain\Banner\UseCase;

use App\Domain\Banner\Repository\BannerRepository;

final class GetBannerDetailUseCase
{
    private BannerRepository $repository;

    public function __construct(BannerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->getById($id);
    }
}
