<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUserSuspensionLogsAction extends BaseController
{
    public function __invoke(Request $request, string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('users.notfound'),
                'status_code' => 404,
            ], 404);
        }

        $logs = $user->suspensionLogs()
            ->with('performer:id,first_name,last_name,avatar_url')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'action_label' => $log->action === 'suspend' ? '凍結' : '解除',
                    'reason' => $log->reason,
                    'performed_by' => $log->performer ? [
                        'id' => $log->performer->id,
                        'name' => $log->performer->full_name,
                        'avatar' => $log->performer->avatar_full_url,
                    ] : null,
                    'created_at' => $log->created_at->format('Y/m/d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'status_code' => 200,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'is_suspended' => $user->is_suspended,
                    'suspended_at' => $user->suspended_at?->format('Y/m/d H:i:s'),
                    'suspension_count' => $user->suspension_count ?? 0,
                ],
                'logs' => $logs,
            ],
        ], 200);
    }
}
