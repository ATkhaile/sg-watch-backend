<?php

namespace App\Http\Actions\Api\Auth;

use App\Http\Controllers\BaseController;
use App\Models\EmailChangeRequest;
use Illuminate\Support\Facades\Response;

class GetPendingEmailChangeAction extends BaseController
{
    public function __invoke()
    {
        $user = auth()->user();

        $pendingRequest = EmailChangeRequest::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        return Response::apiSuccess('メールアドレス情報を取得しました', [
            'current_email' => $user->email,
            'pending_email' => $pendingRequest?->new_email,
            'pending_expires_at' => $pendingRequest?->expires_at?->toISOString(),
        ]);
    }
}
