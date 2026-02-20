<?php

return [

    'list' => [
        'success' => 'Fetched custom links successfully.',
    ],
    'save' => [
        'success' => 'Custom links have been saved.',
        'failed'  => 'Failed to save custom links.',
    ],
    'create' => [
        'success' => 'Custom link created.',
        'failed'  => 'Failed to create custom link.',
    ],
    'summary' => ['message' => 'Summary retrieved successfully.'],
    'graph'   => ['message' => 'Graph data retrieved successfully.'],
    'history' => ['message' => 'Access history retrieved successfully.'],
    'validation' => [
        'custom_links' => [
            'required' => 'The custom_links array is required.',
            'array'    => 'The custom_links field must be an array.',
            'min'      => 'Please provide at least one custom link.',
        ],

        'id' => [
            'required' => 'The id field is required.',
            'integer' => 'The id must be an integer.',
            'exists'  => 'The specified custom link id does not exist.',
        ],

        'name' => [
            'required' => 'The name field is required.',
            'string'   => 'The name must be a string.',
            'max'      => 'The name may not be greater than :max characters.',
        ],

        'prefix' => [
            'required' => 'The prefix field is required.',
            'string'   => 'The prefix must be a string.',
            'max'      => 'The prefix may not be greater than :max characters.',
            'unique'   => 'This prefix has already been taken.',
        ],

        'redirect_url' => [
            'required' => 'The redirect URL field is required.',
            'string'   => 'The redirect URL must be a string.',
            'max'      => 'The redirect URL may not be greater than :max characters.',
            'url'      => 'The redirect URL must be a valid URL.',
        ],

        'action' => [
            'required' => 'The action field is required.',
            'in'       => 'The selected action is invalid.',
        ],

        'order_num' => [
            'required' => 'The order number field is required.',
            'integer'  => 'The order number must be an integer.',
            'min'      => 'The order number must be at least :min.',
        ],
        'view_type' => ['required' => 'view_type is required.', 'in' => 'view_type must be year|month|day.'],
        'year'  => ['integer' => 'year must be integer.'],
        'month' => ['regex' => 'month must be YYYY/MM.'],
        'day'   => ['regex' => 'day must be YYYY/MM/DD.'],
        'page'  => ['integer' => 'Page must be integer.'],
        'limit' => ['integer' => 'Limit must be integer.'],
        'from'  => ['date_format' => 'from must be Y/m/d.'],
        'to'    => ['date_format' => 'to must be Y/m/d.'],
        'direction' => ['in' => 'Direction must be ASC or DESC.'],
    ],

];
