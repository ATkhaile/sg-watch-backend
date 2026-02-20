<?php

namespace App\Http\Requests\Api\Authorization;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class ListPermissionFromUserRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['user_id' => $this->route('user_id')]);

        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __('authorization.user.user_id.required'),
            'user_id.exists' => __('authorization.user.user_id.exists'),
        ];
    }
}
