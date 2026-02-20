<?php

namespace App\Http\Actions\Api\Google;

use App\Domain\Google\UseCase\GoogleCallbackUseCase;
use App\Domain\Google\Factory\GoogleCallbackRequestFactory;
use App\Http\Requests\Api\Google\GoogleCallbackRequest;
use App\Http\Resources\Api\Google\GoogleCallbackResource;
use App\Http\Responders\Api\Google\GoogleCallbackResponder;
use App\Http\Controllers\BaseController;

class GoogleCallbackAction extends BaseController
{
    public function __construct(
        private GoogleCallbackUseCase $googleCallbackUseCase,
        private GoogleCallbackRequestFactory $factory,
        private GoogleCallbackResponder $responder
    ) {}

    public function __invoke(GoogleCallbackRequest $request): GoogleCallbackResource
    {
        $requestEntity = $this->factory->createFromRequest($request);
        $responseEntity = $this->googleCallbackUseCase->__invoke($requestEntity);
        return $this->responder->__invoke($responseEntity);
    }
}
