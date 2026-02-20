<?php

namespace App\Domain\Stripe\Factory;

use App\Domain\Stripe\Entity\RequestCancelRequestEntity;
use Illuminate\Http\Request;

class RequestCancelRequestFactory
{
    public function createFromRequest(Request $request): RequestCancelRequestEntity
    {
        $user = auth()->user() ?? null;

        return new RequestCancelRequestEntity(
            email: $request->get('email', ''),
            userId: $user->id,
            userName: $user->full_name
        );
    }
}
