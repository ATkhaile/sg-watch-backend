<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Domain\ShopOrder\UseCase\HandleStripeWebhookUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeWebhookAction extends BaseController
{
    private HandleStripeWebhookUseCase $useCase;

    public function __construct(HandleStripeWebhookUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature', '');

        $result = $this->useCase->__invoke($payload, $signature);

        return response()->json(
            ['message' => $result['message']],
            $result['success'] ? 200 : 400
        );
    }
}
