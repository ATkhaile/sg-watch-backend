<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class ConfirmEmailChangeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'トークンが必要です',
        ];
    }
}
