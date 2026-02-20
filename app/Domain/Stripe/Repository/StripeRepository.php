<?php

namespace App\Domain\Stripe\Repository;

use App\Domain\Stripe\Entity\CreateCustomerRequestEntity;
use App\Domain\Stripe\Entity\GetPortalLinkRequestEntity;
use App\Domain\Stripe\Entity\RequestCancelRequestEntity;
use App\Domain\Stripe\Entity\SubmitCancelRequestEntity;
use App\Domain\Stripe\Entity\CheckCancelCodeRequestEntity;
use App\Domain\Stripe\Entity\StatusEntity;
use App\Domain\Stripe\Entity\PortalLinkResponseEntity;
use App\Domain\Stripe\Entity\CancelResponseEntity;

interface StripeRepository
{
    public function handleWebhook(CreateCustomerRequestEntity $requestEntity): StatusEntity;
    public function createPortalLink(GetPortalLinkRequestEntity $requestEntity): PortalLinkResponseEntity;
    public function requestCancel(RequestCancelRequestEntity $requestEntity): StatusEntity;
    public function submitCancel(SubmitCancelRequestEntity $requestEntity): CancelResponseEntity;
    public function checkCancelCode(CheckCancelCodeRequestEntity $requestEntity): StatusEntity;
    public function getDashboardStats(int $id): ?array;
    public function getAllDashboardStats(): array;
    public function refreshDashboardStats(int $id): array;
    public function refreshAllDashboardStats(): array;
}
