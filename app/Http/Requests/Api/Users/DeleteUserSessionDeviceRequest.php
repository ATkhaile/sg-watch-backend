<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class DeleteUserSessionDeviceRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    protected function getRequiredPermission(): ?string
    {
        return 'delete-session-device';
    }

    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('sessions', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('users.validation.id.required'),
            'id.integer'  => __('users.validation.id.integer'),
            'id.exists'   => __('users.validation.id.exists'),
        ];
    }
}
