<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class ResetPasswordByTokenRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reset_token' => ['required', 'string'],
            'password' => [
                'required',
                'min:8',
                'max:16',
                'confirmed',
            ],
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'reset_token.required' => __('auth.token_failed'),
            'password.required' => __('auth.validation.password.required'),
            'password.min' => __('auth.validation.password.min'),
            'password.max' => __('auth.validation.password.max'),
            'password.confirmed' => __('auth.validation.password.confirmed'),
            'password_confirmation.required' => __('auth.validation.password_confirmation.required'),
            'password_confirmation.same' => __('auth.validation.password_confirmation.same'),
        ];
    }
}
