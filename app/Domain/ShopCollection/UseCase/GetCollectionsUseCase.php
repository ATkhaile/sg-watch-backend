<?php

namespace App\Domain\ShopCollection\UseCase;

use App\Domain\ShopCollection\Repository\ShopCollectionRepository;

final class GetCollectionsUseCase
{
    private ShopCollectionRepository $repository;

    public function __construct(ShopCollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(?int $userId = null): array
    {
        return $this->repository->getActiveCollections($userId);
    }
}
