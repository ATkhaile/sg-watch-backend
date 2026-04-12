<?php

namespace App\Domain\ShopCollection\UseCase;

use App\Domain\ShopCollection\Repository\ShopCollectionRepository;

final class DeleteCollectionUseCase
{
    private ShopCollectionRepository $repository;

    public function __construct(ShopCollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): array
    {
        return $this->repository->deleteCollection($id);
    }
}
