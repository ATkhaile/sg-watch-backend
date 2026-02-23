<?php

namespace App\Domain\ShopReview\UseCase;

use App\Domain\ShopReview\Repository\ShopReviewRepository;

final class UpdateReviewUseCase
{
    private ShopReviewRepository $repository;

    public function __construct(ShopReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $reviewId, array $data): array
    {
        return $this->repository->update($userId, $reviewId, $data);
    }
}
