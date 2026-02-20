<?php

namespace App\Domain\StripeAccount\Factory;

use App\Domain\StripeAccount\Entity\ExportStripeTransactionsRequestEntity;
use App\Http\Requests\Api\StripeAccount\ExportStripeTransactionsRequest;

class ExportStripeTransactionsRequestFactory
{
    public function createFromRequest(ExportStripeTransactionsRequest $request): ExportStripeTransactionsRequestEntity
    {
        return new ExportStripeTransactionsRequestEntity(
            stripeAccountId: $request->route('stripe_account_id'),
            limit: $request->input('limit'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date')
        );
    }
}
