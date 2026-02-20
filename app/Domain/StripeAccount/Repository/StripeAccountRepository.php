<?php

namespace App\Domain\StripeAccount\Repository;

use App\Domain\StripeAccount\Entity\StripeAccountDetailEntity;
use App\Domain\StripeAccount\Entity\CreateStripeAccountRequestEntity;
use App\Domain\StripeAccount\Entity\UpdateStripeAccountRequestEntity;
use App\Domain\StripeAccount\Entity\DeleteStripeAccountRequestEntity;
use App\Domain\StripeAccount\Entity\StripeAccountEntity;
use App\Domain\StripeAccount\Entity\CustomerSubscriptionEntity;
use App\Domain\StripeAccount\Entity\GetStripeProductsRequestEntity;
use App\Domain\StripeAccount\Entity\StripeProductEntity;
use App\Domain\StripeAccount\Entity\GetStripePricesRequestEntity;
use App\Domain\StripeAccount\Entity\StripePriceEntity;
use App\Domain\StripeAccount\Entity\GetStripePaymentLinksRequestEntity;
use App\Domain\StripeAccount\Entity\StripePaymentLinkEntity;
use App\Domain\StripeAccount\Entity\GetStripeTransactionsRequestEntity;
use App\Domain\StripeAccount\Entity\StripeTransactionEntity;
use App\Domain\StripeAccount\Entity\ExportStripeTransactionsRequestEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface StripeAccountRepository
{
    public function getAllStripeAccount(StripeAccountEntity $entity): LengthAwarePaginator;
    public function getStripeAccountDetail(int $id): ?StripeAccountDetailEntity;
    public function store(CreateStripeAccountRequestEntity $requestEntity): bool;
    public function update(UpdateStripeAccountRequestEntity $requestEntity, int $id): bool;
    public function delete(DeleteStripeAccountRequestEntity $requestEntity): bool;
    public function getUserActiveStripeAccountId(int $userId): ?string;
    public function getAdminCustomerSubscriptions(CustomerSubscriptionEntity $entity, int $adminUserId): LengthAwarePaginator;
    public function getStripeProducts(GetStripeProductsRequestEntity $entity): StripeProductEntity;
    public function getStripePrices(GetStripePricesRequestEntity $entity): StripePriceEntity;
    public function getStripePaymentLinks(GetStripePaymentLinksRequestEntity $entity): StripePaymentLinkEntity;
    public function getStripeTransactions(GetStripeTransactionsRequestEntity $entity): StripeTransactionEntity;
    public function getStripeTransactionsForExport(ExportStripeTransactionsRequestEntity $entity): array;
}
