<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class SessionAppLoginRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => __('auth.validation.email.required_without'),
        ];
    }
}
