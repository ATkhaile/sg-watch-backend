<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class VerifyLoginCodeRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required_without:user_id',
                'email',
                'max:255'
            ],
            'user_id' => [
                'required_without:email',
                'string',
                'max:255',
                'exists:users,user_id'
            ],
            'verification_code' => [
                'required',
                'string',
                'size:6'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => __('auth.validation.email.required_without'),
            'email.email' => __('auth.validation.email.email'),
            'email.max' => __('auth.validation.email.max'),
            'user_id.required_without' => __('auth.validation.user_id.required_without'),
            'user_id.string' => __('auth.validation.user_id.string'),
            'user_id.max' => __('auth.validation.user_id.max'),
            'verification_code.required' => __('auth.validation.verification_code.required'),
            'verification_code.string' => __('auth.validation.verification_code.string'),
            'verification_code.size' => __('auth.validation.verification_code.size'),
        ];
    }
}
