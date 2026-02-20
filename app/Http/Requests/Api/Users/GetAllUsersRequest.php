<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Support\Str;

class GetAllUsersRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    protected function prepareForValidation()
    {
        if ($this->has('search_email_like')) {
            $value = $this->input('search_email_like');
            if (Str::lower($value) === 'true') {
                $this->merge(['search_email_like' => 1]);
            } elseif (Str::lower($value) === 'false') {
                $this->merge(['search_email_like' => 0]);
            }
        }
        if ($this->has('search_email_not')) {
            $value = $this->input('search_email_not');
            if (Str::lower($value) === 'true') {
                $this->merge(['search_email_not' => 1]);
            } elseif (Str::lower($value) === 'false') {
                $this->merge(['search_email_not' => 0]);
            }
        }

        if ($this->has('admin')) {
            $value = $this->input('admin');
            if (Str::lower($value) === 'true') {
                $this->merge(['admin' => true]);
            } elseif (Str::lower($value) === 'false') {
                $this->merge(['admin' => false]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:256',
            'search_email' => 'nullable|max:256',
            'search_email_like' => 'nullable|boolean',
            'search_email_not' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'sort_first_name' => 'nullable|in:ASC,DESC',
            'sort_email' => 'nullable|in:ASC,DESC',
            'sort_created' => 'nullable|in:ASC,DESC',
            'sort_updated' => 'nullable|in:ASC,DESC',
            'admin' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'search.max' => __('users.validation.search.max'),
            'search_email.max' => __('users.validation.search_email.max'),
            'search_email_like.boolean' => __('users.validation.search_email_like.boolean'),
            'search_email_not.boolean' => __('users.validation.search_email_not.boolean'),
            'page.integer' => __('users.validation.page.integer'),
            'page.min' => __('users.validation.page.min'),
            'limit.integer' => __('users.validation.limit.integer'),
            'limit.min' => __('users.validation.limit.min'),
            'sort_first_name.in' => __('users.validation.sort_first_name.in'),
            'sort_email.in' => __('users.validation.sort_email.in'),
            'sort_created.in' => __('users.validation.sort_created.in'),
            'sort_updated.in' => __('users.validation.sort_updated.in'),
            'admin.boolean' => __('users.validation.admin.boolean'),
        ];
    }
}
