<?php

namespace App\Http\Requests\Api\Notifications;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class DeleteNotificationsRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists('notifications', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('notifications.validation.id.required'),
            'id.integer' => __('notifications.validation.id.integer'),
            'id.exists' => __('notifications.validation.id.exists'),
        ];
    }
}
