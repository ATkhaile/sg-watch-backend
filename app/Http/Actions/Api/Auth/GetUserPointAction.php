<?php

namespace App\Http\Actions\Api\Auth;

use App\Http\Controllers\BaseController;
use App\Models\PointHistory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class GetUserPointAction extends BaseController
{
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();

        // Sync user.point từ point_histories (tự động loại bỏ điểm hết hạn)
        PointHistory::syncUserPoint($user->id);
        $user->refresh();

        $expiringIn30Days = PointHistory::where('user_id', $user->id)
            ->where('remaining_point', '>', 0)
            ->where('expired_at', '>', Carbon::now())
            ->where('expired_at', '<=', Carbon::now()->addDays(30))
            ->sum('remaining_point');

        $nearestExpiry = PointHistory::where('user_id', $user->id)
            ->where('remaining_point', '>', 0)
            ->where('expired_at', '>', Carbon::now())
            ->orderBy('expired_at', 'asc')
            ->value('expired_at');

        return response()->json([
            'message' => 'User point retrieved successfully.',
            'status_code' => 200,
            'data' => [
                'point' => $user->point ?? 0,
                'expiring_in_30_days' => (int) $expiringIn30Days,
                'nearest_expiry_date' => $nearestExpiry,
            ],
        ]);
    }
}
