<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class ChangePasswordRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'password_old' => 'required|min:8|max:16',
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
            'password_old.required' => __('auth.validation.password_old.required'),
            'password.required' => __('auth.validation.password.required'),
            'password.min' => __('auth.validation.password.min'),
            'password.max' => __('auth.validation.password.max'),
            'password.confirmed' => __('auth.validation.password.confirmed'),
            'password_confirmation.required' => __('auth.validation.password_confirmation.required'),
            'password_confirmation.same' => __('auth.validation.password_confirmation.same'),
        ];
    }
}
