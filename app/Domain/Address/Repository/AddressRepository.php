<?php

namespace App\Domain\Address\Repository;

interface AddressRepository
{
    public function getAllByUserId(int $userId): array;
    public function getById(int $addressId, int $userId): ?array;
    public function create(int $userId, array $masterData, array $detailData): ?int;
    public function update(int $addressId, int $userId, array $masterData, array $detailData): bool;
    public function delete(int $addressId, int $userId): bool;
}
