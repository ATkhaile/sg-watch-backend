<?php

namespace App\Domain\AdminUser\UseCase;

use App\Domain\AdminUser\Repository\AdminUserRepository;

final class GetAdminUserListUseCase
{
    private AdminUserRepository $repository;

    public function __construct(AdminUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
