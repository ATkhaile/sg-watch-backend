<?php

namespace App\Domain\ShopReview\UseCase;

use App\Domain\ShopReview\Repository\ShopReviewRepository;

final class GetReviewDetailUseCase
{
    private ShopReviewRepository $repository;

    public function __construct(ShopReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $reviewId, ?int $authUserId = null): ?array
    {
        return $this->repository->getById($reviewId, $authUserId);
    }
}
