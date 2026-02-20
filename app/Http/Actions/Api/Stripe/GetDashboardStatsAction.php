<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\GetStripeDashboardStatsUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetDashboardStatsAction extends BaseController
{
    public function __construct(
        private GetStripeDashboardStatsUseCase $getStripeDashboardStatsUseCase
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        $stats = $this->getStripeDashboardStatsUseCase->handle($id);

        if (!$stats) {
            return response()->json([
                'message' => 'Dashboard stats not found. Please refresh to fetch data.',
            ], 404);
        }

        return response()->json($stats);
    }
}
