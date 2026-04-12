<?php

namespace App\Domain\ShopCollection\Repository;

interface ShopCollectionRepository
{
    public function createCollection(array $data): array;
    public function getCollections(): array;
    public function getActiveCollections(): array;
    public function getById(int $id): ?array;
    public function updateCollection(int $id, array $data): array;
    public function deleteCollection(int $id): array;
}
