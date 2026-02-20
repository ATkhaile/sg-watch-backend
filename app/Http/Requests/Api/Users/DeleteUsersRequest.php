<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class DeleteUsersRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('users.validation.id.required'),
            'id.integer' => __('users.validation.id.integer'),
            'id.exists' => __('users.validation.id.exists'),
        ];
    }
}
