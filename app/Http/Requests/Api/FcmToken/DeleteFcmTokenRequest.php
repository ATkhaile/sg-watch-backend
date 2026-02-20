<?php

namespace App\Http\Requests\Api\FcmToken;

use App\Http\Requests\Api\ApiFormRequest;

class DeleteFcmTokenRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token.required' => __('fcm_token.validation.fcm_token.required'),
            'fcm_token.string' => __('fcm_token.validation.fcm_token.string'),
            'fcm_token.max' => __('fcm_token.validation.fcm_token.max'),
        ];
    }
}
