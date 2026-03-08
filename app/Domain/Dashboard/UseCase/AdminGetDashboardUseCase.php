<?php

namespace App\Domain\Dashboard\UseCase;

use App\Domain\Dashboard\Repository\DashboardRepository;

final class AdminGetDashboardUseCase
{
    private DashboardRepository $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getAdminDashboard($filters);
    }
}
