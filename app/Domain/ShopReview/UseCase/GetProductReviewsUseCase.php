<?php

namespace App\Domain\ShopReview\UseCase;

use App\Domain\ShopReview\Repository\ShopReviewRepository;

final class GetProductReviewsUseCase
{
    private ShopReviewRepository $repository;

    public function __construct(ShopReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $productId, int $perPage = 15, ?int $authUserId = null): array
    {
        return $this->repository->getByProduct($productId, $perPage, $authUserId);
    }
}
