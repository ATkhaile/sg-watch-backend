<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\GetPortalLinkUseCase;
use App\Domain\Stripe\Factory\GetPortalLinkRequestFactory;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Stripe\PortalLinkResource;
use App\Http\Responders\Api\Stripe\PortalLinkResponder;

class GetPortalLinkAction extends BaseController
{
    public function __construct(
        private GetPortalLinkUseCase $getPortalLinkUseCase,
        private GetPortalLinkRequestFactory $factory,
        private PortalLinkResponder $responder
    ) {
    }

    public function __invoke(): PortalLinkResource
    {
        $requestEntity = $this->factory->createFromAuth();
        $responseEntity = $this->getPortalLinkUseCase->__invoke($requestEntity);

        return $this->responder->__invoke($responseEntity);
    }
}
