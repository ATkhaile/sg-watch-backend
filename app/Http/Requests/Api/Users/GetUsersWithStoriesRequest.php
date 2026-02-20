<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Api\ApiFormRequest;

class GetUsersWithStoriesRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => __('users.validation.page.integer'),
            'page.min' => __('users.validation.page.min'),
            'limit.integer' => __('users.validation.limit.integer'),
            'limit.min' => __('users.validation.limit.min'),
        ];
    }
}
