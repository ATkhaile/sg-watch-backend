<?php

namespace App\Http\Requests\Api\Authorization;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class DeletePermissionRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('permissions', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('authorization.permission.validation.id.required'),
            'id.integer' => __('authorization.permission.validation.id.integer'),
            'id.exists' => __('authorization.permission.validation.id.exists'),
        ];
    }
}
