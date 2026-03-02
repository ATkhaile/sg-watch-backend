<?php

namespace App\Domain\ShopProduct\Repository;

interface ShopProductRepository
{
    public function getList(array $filters, ?int $userId = null): array;
    public function getBySlug(string $slug, ?int $userId = null): ?array;
    public function adminGetList(array $filters): array;
    public function adminGetById(int $id): ?array;
    public function getBestSellers(int $limit): array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function delete(int $id): array;
    public function updateFeaturedProducts(array $productIds): array;
    public function getFeaturedProducts(): array;
}
