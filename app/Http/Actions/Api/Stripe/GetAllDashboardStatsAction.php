<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\GetStripeDashboardStatsUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetAllDashboardStatsAction extends BaseController
{
    public function __construct(
        private GetStripeDashboardStatsUseCase $getStripeDashboardStatsUseCase
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $stats = $this->getStripeDashboardStatsUseCase->handleAll();

        return response()->json($stats);
    }
}
