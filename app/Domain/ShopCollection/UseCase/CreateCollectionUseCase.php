<?php

namespace App\Domain\ShopCollection\UseCase;

use App\Domain\ShopCollection\Repository\ShopCollectionRepository;

final class CreateCollectionUseCase
{
    private ShopCollectionRepository $repository;

    public function __construct(ShopCollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->createCollection($data);
    }
}
