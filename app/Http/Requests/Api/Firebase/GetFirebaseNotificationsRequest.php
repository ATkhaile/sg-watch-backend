<?php

namespace App\Http\Requests\Api\Firebase;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class GetFirebaseNotificationsRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
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
            'page.integer' => __('firebase.validation.page.integer'),
            'page.min' => __('firebase.validation.page.min'),
            'limit.integer' => __('firebase.validation.per_page.integer'),
            'limit.min' => __('firebase.validation.per_page.min'),
            'fcm_token.required' => __('firebase.validation.fcm_token.required'),
            'fcm_token.string' => __('firebase.validation.fcm_token.string'),
            'fcm_token.max' => __('firebase.validation.fcm_token.max'),
            'fcm_token.exists' => __('firebase.validation.fcm_token.exists'),
        ];
    }
}
