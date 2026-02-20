<?php

namespace App\Http\Requests\Api\Firebase;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class GetFirebaseUnreadNotificationsRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token' => [
                'required',
                'string',
                'max:255',
                Rule::exists('fcm_tokens', 'fcm_token')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token.required' => __('firebase.validation.fcm_token.required'),
            'fcm_token.string' => __('firebase.validation.fcm_token.string'),
            'fcm_token.max' => __('firebase.validation.fcm_token.max'),
            'fcm_token.exists' => __('firebase.validation.fcm_token.exists'),
        ];
    }
}
