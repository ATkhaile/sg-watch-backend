<?php

namespace App\Domain\ShopCollection\Repository;

interface ShopCollectionRepository
{
    public function createCollection(array $data): array;
    public function getCollections(): array;
    public function getActiveCollections(): array;
}
