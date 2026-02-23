<?php

namespace App\Domain\ShopReview\UseCase;

use App\Domain\ShopReview\Repository\ShopReviewRepository;

final class GetMyReviewsUseCase
{
    private ShopReviewRepository $repository;

    public function __construct(ShopReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $perPage = 15): array
    {
        return $this->repository->getMyReviews($userId, $perPage);
    }
}
