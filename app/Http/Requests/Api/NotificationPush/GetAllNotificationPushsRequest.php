<?php

namespace App\Http\Requests\Api\NotificationPush;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class GetAllNotificationPushsRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'page'  => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'sort' => [
                'nullable',
                'string',
                Rule::in(Schema::getColumnListing('notification_pushs')),
            ],
            'direction' => 'nullable|in:ASC,DESC,asc,desc',
            'search'    => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer'   => __('notification_push.validation.page.integer'),
            'page.min'       => __('notification_push.validation.page.min'),
            'limit.integer'  => __('notification_push.validation.limit.integer'),
            'limit.min'      => __('notification_push.validation.limit.min'),
            'sort.string'    => __('notification_push.validation.sort.string'),
            'direction.in'   => __('notification_push.validation.direction.in'),
            'search.string'  => __('notification_push.validation.search.string'),
            'search.max'     => __('notification_push.validation.search.max'),
        ];
    }
}
