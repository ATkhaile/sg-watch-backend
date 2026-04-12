<?php

namespace App\Domain\ShopInventoryHistory\Repository;

interface ShopInventoryHistoryRepository
{
    public function getList(array $filters): array;
}
