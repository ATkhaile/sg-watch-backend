<?php

namespace App\Http\Actions\Api\UserProfile;

use App\Domain\UserProfile\UseCase\UpdateUserProfileUseCase;
use App\Domain\UserProfile\Factory\UpdateUserProfileRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\UserProfile\UpdateUserProfileRequest;
use App\Http\Resources\Api\UserProfile\ActionResource;
use App\Http\Responders\Api\UserProfile\ActionResponder;
use Illuminate\Support\Facades\Auth;

class UpdateUserProfileAction extends BaseController
{
    public function __construct(
        private UpdateUserProfileUseCase $updateUserProfileUseCase,
        private UpdateUserProfileRequestFactory $factory,
        private ActionResponder $responder
    ) {
    }

    public function __invoke(UpdateUserProfileRequest $request): ActionResource
    {
        $accountId = Auth::guard('member')->id();
        $entity = $this->factory->createFromRequest($request);
        $result = $this->updateUserProfileUseCase->__invoke($accountId, $entity);
        return $this->responder->__invoke($result);
    }
}
