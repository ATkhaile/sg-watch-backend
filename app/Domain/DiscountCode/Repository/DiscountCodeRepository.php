<?php

namespace App\Domain\DiscountCode\Repository;

interface DiscountCodeRepository
{
    public function getList(array $filters): array;

    public function getById(int $id): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): array;

    public function delete(int $id): array;

    public function getByCode(string $code): ?array;
}
