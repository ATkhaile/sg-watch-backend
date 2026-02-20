<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class VerifyPasswordOtpRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('auth.validation.email.required'),
            'email.email' => __('auth.validation.email.email'),
            'otp.required' => __('auth.validation.verification_code.required'),
            'otp.string' => __('auth.validation.verification_code.string'),
            'otp.size' => __('auth.validation.verification_code.string'),
        ];
    }
}
