<?php

namespace App\Domain\ShopFavorite\UseCase;

use App\Domain\ShopFavorite\Repository\ShopFavoriteRepository;

final class ToggleFavoriteUseCase
{
    private ShopFavoriteRepository $repository;

    public function __construct(ShopFavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $productId): array
    {
        return $this->repository->toggle($userId, $productId);
    }
}
