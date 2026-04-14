<?php

namespace App\Domain\ShopReview\Repository;

interface ShopReviewRepository
{
    public function create(int $userId, array $data): array;
    public function update(int $userId, int $reviewId, array $data): array;
    public function delete(int $userId, int $reviewId): array;
    public function getById(int $reviewId, ?int $authUserId = null): ?array;
    public function getByProduct(int $productId, int $perPage, ?int $authUserId = null): array;
    public function getMyReviews(int $userId, int $perPage): array;
}
