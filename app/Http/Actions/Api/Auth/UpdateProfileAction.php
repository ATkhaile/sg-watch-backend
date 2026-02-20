<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\Factory\UpdateProfileRequestFactory;
use App\Domain\Auth\UseCase\UpdateProfileUseCase;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\Auth\UpdateProfileActionResource;
use App\Http\Responders\Api\Auth\UpdateProfileActionResponder;

class UpdateProfileAction extends BaseController
{
    private UpdateProfileUseCase $updateProfileUseCase;
    private UpdateProfileRequestFactory $factory;
    private UpdateProfileActionResponder $responder;

    public function __construct(
        UpdateProfileUseCase $updateProfileUseCase,
        UpdateProfileRequestFactory $factory,
        UpdateProfileActionResponder $responder
    ) {
        $this->updateProfileUseCase = $updateProfileUseCase;
        $this->factory = $factory;
        $this->responder = $responder;
    }

    public function __invoke(UpdateProfileRequest $request): UpdateProfileActionResource
    {
        $entity = $this->factory->createFromRequest($request);
        $userInfo = $this->updateProfileUseCase->__invoke($entity);

        return $this->responder->__invoke($userInfo);
    }
}
