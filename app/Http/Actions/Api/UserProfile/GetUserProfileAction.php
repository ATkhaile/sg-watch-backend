<?php

namespace App\Http\Actions\Api\UserProfile;

use App\Domain\UserProfile\UseCase\GetUserProfileUseCase;
use App\Domain\UserProfile\Factory\GetUserProfileRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\UserProfile\GetUserProfileRequest;
use App\Http\Resources\Api\UserProfile\GetUserProfileActionResource;
use App\Http\Responders\Api\UserProfile\GetUserProfileActionResponder;

class GetUserProfileAction extends BaseController
{
    public function __construct(
        private GetUserProfileUseCase $getUserProfileUseCase,
        private GetUserProfileRequestFactory $factory,
        private GetUserProfileActionResponder $responder
    ) {
    }

    public function __invoke(GetUserProfileRequest $request): GetUserProfileActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $profile = $this->getUserProfileUseCase->__invoke($entity);
        return $this->responder->__invoke($profile);
    }
}
