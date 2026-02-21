<?php

namespace App\Domain\ShopProduct\Repository;

interface ShopProductRepository
{
    public function getList(array $filters, ?int $userId = null): array;
    public function getBySlug(string $slug, ?int $userId = null): ?array;
}
