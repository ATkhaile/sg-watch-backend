<?php

namespace App\Http\Requests\Api\Google;

use App\Http\Requests\Api\ApiFormRequest;

class GoogleCallbackRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'code.required' => __('google.validation.code.required'),
            'code.string' => __('google.validation.code.string'),
        ];
    }
}
