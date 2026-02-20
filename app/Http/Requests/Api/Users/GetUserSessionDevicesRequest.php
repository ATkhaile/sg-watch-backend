<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetUserSessionDevicesRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => (int) $this->route('id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('users', 'id')->whereNull('deleted_at'),
            ],

            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'sort_created' => 'nullable|in:ASC,DESC',
            'sort_updated' => 'nullable|in:ASC,DESC',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __('users.validation.user_id.required'),
            'user_id.integer'  => __('users.validation.user_id.integer'),
            'user_id.min'      => __('users.validation.user_id.min'),
            'user_id.exists'   => __('users.validation.user_id.exists'),

            'page.integer'     => __('users.validation.page.integer'),
            'page.min'         => __('users.validation.page.min'),

            'limit.integer'    => __('users.validation.limit.integer'),
            'limit.min'        => __('users.validation.limit.min'),
            'sort_created.in' => __('users.validation.sort_created.in'),
            'sort_updated.in' => __('users.validation.sort_updated.in'),

        ];
    }
}
