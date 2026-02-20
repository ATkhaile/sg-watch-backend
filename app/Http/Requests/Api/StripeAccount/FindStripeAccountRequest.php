<?php

namespace App\Http\Requests\Api\StripeAccount;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class FindStripeAccountRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('stripe_accounts', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('stripe_account.validation.id.required'),
            'id.integer' => __('stripe_account.validation.id.integer'),
            'id.exists' => __('stripe_account.validation.id.exists'),
        ];
    }
}
