<?php

namespace App\Http\Actions\Api\Firebase;

use App\Domain\Firebase\UseCase\GetFirebaseNotificationDetailUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Firebase\UpdateFirebaseNotificationReadedRequest;
use App\Http\Resources\Api\Firebase\GetFirebaseNotificationDetailActionResource;
use App\Http\Responders\Api\Firebase\GetFirebaseNotificationDetailActionResponder;
use Illuminate\Http\JsonResponse;

class GetFirebaseNotificationDetailAction extends BaseController
{
    public function __construct(
        private GetFirebaseNotificationDetailUseCase $useCase,
        private GetFirebaseNotificationDetailActionResponder $responder
    ) {
    }

    public function __invoke(UpdateFirebaseNotificationReadedRequest $request, int $id): GetFirebaseNotificationDetailActionResource|JsonResponse
    {
        $notification = $this->useCase->__invoke($id, $request->input('fcm_token'));

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => __('firebase.detail.not_found'),
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return $this->responder->__invoke($notification);
    }
}
