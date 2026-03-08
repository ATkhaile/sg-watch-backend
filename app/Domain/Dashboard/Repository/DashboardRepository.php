<?php

namespace App\Domain\Dashboard\Repository;

interface DashboardRepository
{
    public function getAdminDashboard(array $filters): array;
}
