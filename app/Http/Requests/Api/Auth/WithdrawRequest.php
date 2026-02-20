<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class WithdrawRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [];
    }
}
