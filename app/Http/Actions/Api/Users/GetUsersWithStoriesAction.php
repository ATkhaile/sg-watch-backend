<?php

namespace App\Http\Actions\Api\Users;

use App\Domain\Users\UseCase\GetUsersWithStoriesUseCase;
use App\Domain\Users\Factory\GetUsersWithStoriesRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Users\GetUsersWithStoriesRequest;
use App\Http\Resources\Api\Users\GetUsersWithStoriesActionResource;
use App\Http\Responders\Api\Users\GetUsersWithStoriesActionResponder;

class GetUsersWithStoriesAction extends BaseController
{
    public function __construct(
        private GetUsersWithStoriesUseCase $useCase,
        private GetUsersWithStoriesRequestFactory $factory,
        private GetUsersWithStoriesActionResponder $responder
    ) {
    }

    public function __invoke(GetUsersWithStoriesRequest $request): GetUsersWithStoriesActionResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $entity = $this->useCase->__invoke($requestEntity);
        return $this->responder->__invoke($entity);
    }
}
