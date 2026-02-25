<?php

namespace App\Domain\AdminUser\UseCase;

use App\Domain\AdminUser\Repository\AdminUserRepository;

final class CreateAdminUserUseCase
{
    private AdminUserRepository $repository;

    public function __construct(AdminUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
