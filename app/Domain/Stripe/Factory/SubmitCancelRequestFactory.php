<?php

namespace App\Domain\Stripe\Factory;

use App\Domain\Stripe\Entity\SubmitCancelRequestEntity;
use Illuminate\Http\Request;

class SubmitCancelRequestFactory
{
    public function createFromRequest(Request $request): SubmitCancelRequestEntity
    {
        return new SubmitCancelRequestEntity(
            requestCode: $request->get('request_code', ''),
            reason: trim($request->get('reason', '')),
            password: $request->get('password', '')
        );
    }
}
