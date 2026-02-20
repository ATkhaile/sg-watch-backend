<?php

namespace App\Http\Responders\Api\StripeAccount;

use App\Domain\StripeAccount\Entity\StripeTransactionEntity;
use App\Http\Resources\Api\StripeAccount\GetStripeTransactionsActionResource;

final class GetStripeTransactionsActionResponder
{
    public function __invoke(StripeTransactionEntity $stripeTransactionEntity): GetStripeTransactionsActionResource
    {
        $resource = $this->makeResource($stripeTransactionEntity);
        return new GetStripeTransactionsActionResource($resource);
    }

    public function makeResource(StripeTransactionEntity $stripeTransactionEntity)
    {
        return [
            'transactions' => $stripeTransactionEntity->getTransactions(),
            'total' => $stripeTransactionEntity->getTotal(),
            'has_more' => $stripeTransactionEntity->getHasMore(),
            'next_cursor' => $stripeTransactionEntity->getNextCursor(),
            'today_stats' => $stripeTransactionEntity->getTodayStats(),
            'status_code' => $stripeTransactionEntity->getStatus(),
        ];
    }
}
