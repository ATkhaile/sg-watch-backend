<?php

namespace App\Http\Requests\Api\NotificationPush;


use App\Http\Requests\Api\ApiFormRequest;
use App\Models\NotificationPush;
use Illuminate\Validation\Rule;

class DeleteNotificationPushRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => [
                'required',
                'integer',
                Rule::exists(NotificationPush::class, 'id')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('notification_push.validation.id.required'),
            'id.integer'  => __('notification_push.validation.id.integer'),
            'id.exists'   => __('notification_push.validation.id.exists'),
        ];
    }
}
