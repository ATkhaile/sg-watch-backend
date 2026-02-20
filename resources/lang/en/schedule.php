<?php

return [
    'validation' => [
        'page' => [
            'integer' => 'The page must be an integer.',
            'min' => 'The page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'The limit must be an integer.',
            'min' => 'The limit must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'The per page must be an integer.',
            'min' => 'The per page must be at least 1.',
            'max' => 'The per page may not be greater than 100.',
        ],
        'search' => [
            'string' => 'The search must be a string.',
            'max' => 'The search may not be greater than 255 characters.',
        ],
        'sort' => [
            'string' => 'The sort must be a string.',
            'in' => 'The selected sort field is invalid.',
        ],
        'direction' => [
            'in' => 'The direction must be either ASC or DESC.',
        ],
        'id' => [
            'required' => 'The ID is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
        'schedule' => [
            'required' => 'The schedule is required.',
            'array' => 'The schedule must be an array.',
        ],
        'category_id' => [
            'integer' => 'The category ID must be an integer.',
            'exists' => 'The selected category ID is invalid.',
        ],
        'schedule_tags' => [
            'array' => 'The schedule tags must be an array.',
            'required' => 'The schedule tags are required.',
            'integer' => 'The schedule tag ID must be an integer.',
            'exists' => 'The selected schedule tag ID is invalid.',
        ],
        'schedule_places' => [
            'required' => 'Please select at least one place.',
            'array' => 'The places must be an array.',
            'integer' => 'Each place ID must be an integer.',
            'exists' => 'The selected place ID is invalid.',
            'string' => 'Each place name must be a string.',
            'max' => 'Each place name may not be greater than 255 characters.',
            'distinct' => 'The place names must be unique.',
        ],
        'schedule_persons' => [
            'required' => 'Please select at least one user.',
            'array' => 'The users must be an array.',
            'integer' => 'Each user ID must be an integer.',
            'exists' => 'The selected user ID is invalid.',
            'string' => 'Each user name must be a string.',
            'max' => 'Each user name may not be greater than 255 characters.',
            'distinct' => 'The user names must be unique.',
        ],
        'schedule_services' => [
            'required' => 'Please select at least one service.',
            'array' => 'The services must be an array.',
            'integer' => 'Each service ID must be an integer.',
            'exists' => 'The selected service ID is invalid.',
            'string' => 'Each service name must be a string.',
            'max' => 'Each service name may not be greater than 255 characters.',
            'distinct' => 'The service names must be unique.',
        ],
        'schedule_custom_values' => [
            'required' => 'The schedule custom values are required.',
            'array' => 'The schedule custom values must be an array.',
            'label' => [
                'required' => 'The label is required.',
                'string' => 'The label must be a string.',
                'max' => 'The label may not be greater than 255 characters.',
            ],
            'type' => [
                'required' => 'The type is required.',
                'in' => 'The selected type is invalid.',
                'string' => 'The type must be a string.',
            ],
            'price' => [
                'required' => 'The price is required.',
                'integer' => 'The price must be an integer.',
                'min' => 'The price must be at least 0.',
                'max' => 'The price may not be greater than 9999999999.',
            ],
            'unit' => [
                'required' => 'The unit is required.',
                'string' => 'The unit must be a string.',
                'max' => 'The unit may not be greater than 255 characters.',
            ],
            'datetime' => [
                'required' => 'The datetime is required.',
                'date_format' => 'The datetime must be in format Y/m/d H:i:s.',
            ],
            'value' => [
                'required' => 'The value is required.',
                'string' => 'The value must be a string.',
                'max' => 'The value may not be greater than 255 characters.',
            ],
        ],
        'title' => [
            'required' => 'The title is required.',
            'string' => 'The title must be a string.',
            'max' => 'The title may not be greater than 255 characters.',
        ],
        'body' => [
            'required' => 'The body is required.',
            'string' => 'The body must be a string.',
            'max' => 'The body may not be greater than 10000 characters.',
        ],
        'start_time' => [
            'required' => 'The start time is required.',
            'date_format' => 'The start time must be in format Y/m/d H:i:s.',
        ],
        'end_time' => [
            'required' => 'The end time is required.',
            'date_format' => 'The end time must be in format Y/m/d H:i:s.',
            'after_or_equal' => 'The end time must be greater than or equal to the start time.',
        ],
        'page' => [
            'integer' => 'The page must be an integer.',
            'min' => 'The page must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'The per page must be an integer.',
            'min' => 'The per page must be at least 1.',
            'max' => 'The per page may not be greater than 100.',
        ],
        'search' => [
            'string' => 'The search must be a string.',
            'max' => 'The search may not be greater than 255 characters.',
        ],
        'direction' => [
            'in' => 'The direction must be either ASC or DESC.',
        ],
        'sort' => [
            'string' => 'The sort must be a string.',
            'in' => 'The selected sort field is invalid.',
        ],
        'id' => [
            'required' => 'The ID is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
    ],
    'list' => [
        'message' => 'Schedules List',
    ],
    'create' => [
        'success' => 'Schedule created successfully.',
        'failed' => 'Failed to create schedule.',
    ],
    'find' => [
        'message' => 'Schedule detail',
    ],
    'update' => [
        'success' => 'Schedule updated successfully.',
        'failed' => 'Failed to update schedule.',
    ],
    'delete' => [
        'success' => 'Schedule deleted successfully.',
        'failed' => 'Failed to delete schedule.',
    ],
];
