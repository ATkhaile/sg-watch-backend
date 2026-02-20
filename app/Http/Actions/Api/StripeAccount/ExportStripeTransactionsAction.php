<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Http\Requests\Api\StripeAccount\ExportStripeTransactionsRequest;
use App\Domain\StripeAccount\Factory\ExportStripeTransactionsRequestFactory;
use App\Domain\StripeAccount\UseCase\ExportStripeTransactionsUseCase;
use Maatwebsite\Excel\Facades\Excel;

class ExportStripeTransactionsAction
{
    private ExportStripeTransactionsRequestFactory $factory;
    private ExportStripeTransactionsUseCase $useCase;

    public function __construct(
        ExportStripeTransactionsRequestFactory $factory,
        ExportStripeTransactionsUseCase $useCase
    ) {
        $this->factory = $factory;
        $this->useCase = $useCase;
    }

    public function __invoke(ExportStripeTransactionsRequest $request)
    {
        $entity = $this->factory->createFromRequest($request);
        $export = $this->useCase->execute($entity);

        // Generate filename with timestamp
        $filename = 'stripe_transactions_' . date('YmdHis') . '.xlsx';

        // Return Excel download
        return Excel::download($export, $filename);
    }
}
