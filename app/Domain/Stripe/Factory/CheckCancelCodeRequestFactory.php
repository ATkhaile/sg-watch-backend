<?php

namespace App\Domain\Stripe\Factory;

use App\Domain\Stripe\Entity\CheckCancelCodeRequestEntity;
use Illuminate\Http\Request;

class CheckCancelCodeRequestFactory
{
    public function createFromRequest(Request $request): CheckCancelCodeRequestEntity
    {
        return new CheckCancelCodeRequestEntity(
            requestCode: $request->get('request_code', '')
        );
    }
}
