<?php

namespace App\Http\Requests\Api\FcmToken;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\AppVersion;
use Illuminate\Validation\Rule;

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
            'device_name'   => 'nullable|string|max:255',
            'app_version_name' => [
                'nullable',
                'string',
                Rule::exists(AppVersion::class, 'version_name')->whereNull('deleted_at'),
            ],
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
            'device_name.max'    => __('fcm_token.validation.device_name.max'),

            'app_version_name.string' => __('fcm_token.validation.app_version_name.string'),
            'app_version_name.exists' => __('fcm_token.validation.app_version_name.exists'),
            'x_app_id.string' => __('fcm_token.validation.app_id.string'),
            'x_app_id.max'    => __('fcm_token.validation.app_id.max'),
        ];
    }
}
