<?php

namespace App\Domain\ShopProductColor\Repository;

interface ShopProductColorRepository
{
    public function getByProductId(int $productId): array;
    public function getById(int $id): ?array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function delete(int $id): array;
}
