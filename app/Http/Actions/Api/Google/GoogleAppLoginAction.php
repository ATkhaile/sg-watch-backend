<?php

namespace App\Http\Actions\Api\Google;

use App\Domain\Google\UseCase\GoogleAppLoginUseCase;
use App\Domain\Google\Factory\GoogleAppLoginRequestFactory;
use App\Http\Requests\Api\Google\GoogleAppLoginRequest;
use App\Http\Resources\Api\Google\GoogleAppLoginResource;
use App\Http\Responders\Api\Google\GoogleAppLoginResponder;
use App\Http\Controllers\BaseController;

class GoogleAppLoginAction extends BaseController
{
    public function __construct(
        private GoogleAppLoginUseCase $googleAppLoginUseCase,
        private GoogleAppLoginRequestFactory $factory,
        private GoogleAppLoginResponder $responder
    ) {
    }

    public function __invoke(GoogleAppLoginRequest $request): GoogleAppLoginResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $responseEntity = $this->googleAppLoginUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($responseEntity);
    }
}