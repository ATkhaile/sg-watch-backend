<?php

namespace App\Http\Requests\Api\StripeAccount;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Support\Str;

class GetAllStripeAccountRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    protected function prepareForValidation()
    {
        if ($this->has('search_name_like')) {
            $value = $this->input('search_name_like');
            if (Str::lower($value) === 'true') {
                $this->merge(['search_name_like' => 1]);
            } elseif (Str::lower($value) === 'false') {
                $this->merge(['search_name_like' => 0]);
            }
        }
        if ($this->has('search_name_not')) {
            $value = $this->input('search_name_not');
            if (Str::lower($value) === 'true') {
                $this->merge(['search_name_not' => 1]);
            } elseif (Str::lower($value) === 'false') {
                $this->merge(['search_name_not' => 0]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'search_name' => 'nullable|max:256',
            'search_user_id' => 'nullable|int',
            'search_name_like' => 'nullable|boolean',
            'search_name_not' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'sort_name' => 'nullable|in:ASC,DESC',
            'sort_created' => 'nullable|in:ASC,DESC',
            'sort_updated' => 'nullable|in:ASC,DESC',
        ];
    }

    public function messages(): array
    {
        return [
            'search_name.max' => __('stripe_account.validation.search_name.max'),
            'search_user_id.int' => __('stripe_account.validation.search_user_id.int'),
            'search_name_like.boolean' => __('stripe_account.validation.search_name_like.boolean'),
            'search_name_not.boolean' => __('stripe_account.validation.search_name_not.boolean'),
            'page.integer' =>  __('stripe_account.validation.page.integer'),
            'page.min' => __('stripe_account.validation.page.min'),
            'limit.integer' =>  __('stripe_account.validation.limit.integer'),
            'limit.min' =>  __('stripe_account.validation.limit.min'),
            'sort_name.in' =>  __('stripe_account.validation.sort_name.in'),
            'sort_created.in' =>  __('stripe_account.validation.sort_created.in'),
            'sort_updated.in' =>  __('stripe_account.validation.sort_updated.in'),
        ];
    }
}
