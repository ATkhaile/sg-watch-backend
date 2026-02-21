<?php

namespace App\Domain\ShopFavorite\Repository;

interface ShopFavoriteRepository
{
    public function toggle(int $userId, int $productId): array;
    public function getList(int $userId): array;
}
