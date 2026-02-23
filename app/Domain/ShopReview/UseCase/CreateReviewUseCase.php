<?php

namespace App\Domain\ShopReview\UseCase;

use App\Domain\ShopReview\Repository\ShopReviewRepository;

final class CreateReviewUseCase
{
    private ShopReviewRepository $repository;

    public function __construct(ShopReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, array $data): array
    {
        return $this->repository->create($userId, $data);
    }
}
