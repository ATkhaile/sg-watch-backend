<?php

namespace App\Domain\ShopFavorite\UseCase;

use App\Domain\ShopFavorite\Repository\ShopFavoriteRepository;

final class GetFavoriteListUseCase
{
    private ShopFavoriteRepository $repository;

    public function __construct(ShopFavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId): array
    {
        return $this->repository->getList($userId);
    }
}
