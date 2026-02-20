<?php

return [
    'get_all' => [
        'success' => 'Work managements retrieved successfully.',
        'failed' => 'Failed to retrieve work managements.',
    ],
    'save' => [
        'success' => 'Work management created successfully.',
        'failed' => 'Failed to create work management.',
    ],
    'update' => [
        'success' => 'Work management updated successfully.',
        'failed' => 'Failed to update work management.',
    ],
    'validation' => [
        'user_id' => [
            'required' => 'The user ID field is required.',
            'integer' => 'The user ID must be an integer.',
            'exists' => 'The specified user ID does not exist.',
        ],
        'required' => 'The work managements field is required.',
        'array' => 'The work managements field must be an array.',
        'work_management_id' => [
            'integer' => 'Each work management ID must be an integer.',
            'exists' => 'The specified work management ID does not exist.',
        ],
        'order_num' => [
            'required' => 'The order number for each work management is required.',
            'integer' => 'The order number for each work management must be an integer.',
            'min' => 'The order number for each work management must be at least :min.',
        ],
        'public_date' => [
            'date_format' => 'The public date for each work management must be in the format Y/m/d.',
        ],
        'status' => [
            'integer' => 'The status for each work management must be an integer.',
            'in' => 'The status for each work management is invalid.',
        ],
        'work_tasks' => [
            'required' => 'The work tasks field is required for each work management.',
            'array' => 'The work tasks field must be an array for each work management.',
            'work_task_url' => [
                'required' => 'The URL for each work task is required.',
                'string' => 'The URL for each work task must be a string.',
                'max' => 'The URL for each work task must not exceed :max characters.',
                'url' => 'The URL for each work task must be a valid URL.',
            ],
            'order_num' => [
                'required' => 'The order number for each work task is required.',
                'integer' => 'The order number for each work task must be an integer.',
                'min' => 'The order number for each work task must be at least :min.',
            ],
        ],
    ],
];
