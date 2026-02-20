<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class LogoutRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'fcm_token.string' => __('fcm_token.validation.fcm_token.string'),
            'fcm_token.max' => __('fcm_token.validation.fcm_token.max'),
            
        ];
    }

}
