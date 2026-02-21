<?php

namespace App\Domain\ShopProduct\UseCase;

use App\Domain\ShopProduct\Repository\ShopProductRepository;

final class GetProductDetailUseCase
{
    private ShopProductRepository $repository;

    public function __construct(ShopProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $slug, ?int $userId = null): ?array
    {
        return $this->repository->getBySlug($slug, $userId);
    }
}
