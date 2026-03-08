<?php

namespace App\Http\Requests\Api\FcmToken;

use App\Http\Requests\Api\ApiFormRequest;

class CreateFcmTokenRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'x_app_id' => $this->header('X-App-Id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'fcm_token' => 'required|string|max:255',
            'device_name' => 'nullable|string|max:255',
            'x_app_id' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token.required' => __('fcm_token.validation.fcm_token.required'),
            'fcm_token.string' => __('fcm_token.validation.fcm_token.string'),
            'fcm_token.max' => __('fcm_token.validation.fcm_token.max'),
            'device_name.string' => __('fcm_token.validation.device_name.string'),
            'device_name.max' => __('fcm_token.validation.device_name.max'),
            'x_app_id.string' => __('fcm_token.validation.app_id.string'),
            'x_app_id.max' => __('fcm_token.validation.app_id.max'),
        ];
    }
}
