<?php

namespace App\Http\Actions\Api\StripeAccount;

use App\Domain\StripeAccount\UseCase\TestStripeConnectionUseCase;
use App\Domain\StripeAccount\Factory\TestStripeConnectionRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\StripeAccount\TestStripeConnectionRequest;
use Illuminate\Http\JsonResponse;

class TestStripeConnectionAction extends BaseController
{
    public function __construct(
        private TestStripeConnectionUseCase $testStripeConnectionUseCase,
        private TestStripeConnectionRequestFactory $factory,
    ) {}

    public function __invoke(TestStripeConnectionRequest $request): JsonResponse
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $result = $this->testStripeConnectionUseCase->__invoke($requestEntity);

        $statusCode = $result->success ? 200 : 400;

        return response()->json([
            'message' => $result->success ? '接続テスト成功' : '接続テスト失敗',
            'data' => $result->toArray(),
        ], $statusCode);
    }
}
