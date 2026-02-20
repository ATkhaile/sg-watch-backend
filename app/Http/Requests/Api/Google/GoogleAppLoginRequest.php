<?php

namespace App\Http\Requests\Api\Google;

use App\Http\Requests\Api\ApiFormRequest;

class GoogleAppLoginRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => __('google.validation.token.required'),
            'token.string' => __('google.validation.token.string'),
        ];
    }
}
