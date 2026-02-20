<?php

return [
    'import' => [
        'success' => 'Schedule imported successfully',
        'failed'  => 'Failed to import schedule',
        'invalid_file' => 'The uploaded file is invalid.',
    ],
    'template' => [
        'success' => 'Template downloaded successfully'
    ],
    'update' => [
        'success' => 'Schedule updated successfully',
        'failed'  => 'Failed to update schedule',
    ],
    'list' => [
        'message' => 'Schedule list',
        'success' => 'Schedule list retrieved successfully',
    ],
    'detail' => [
        'message' => 'Schedule detail',
        'success' => 'Schedule detail retrieved successfully',
    ],
    'not_found' => 'Schedule not found',
    'validation' => [
        'shop_id' => [
            'required' => 'Shop ID is required.',
            'integer'  => 'Shop ID must be an integer.',
            'exists'   => 'The specified Shop ID does not exist.',
        ],
        'start_date' => [
            'required'    => 'Start date is required.',
            'date_format' => 'Start date must be in Y-m-d format.',
        ],
        'date' => [
            'required'    => 'Date is required.',
            'date_format' => 'Date must be in Y-m-d format.',
            'after_or_equal' => 'Date must be today or in the future.',
        ],
        'time_start' => [
            'required' => 'Start time is required.',
            'integer'  => 'Start time must be an integer.',
            'min'      => 'Start time must be at least 6.',
            'max'      => 'Start time may not be greater than 23.',
        ],
        'time' => [
            'required' => 'Time is required.',
            'integer'  => 'Time must be an integer.',
        ],
        'status' => [
            'required' => 'Status is required.',
            'integer'  => 'Status must be an integer.',
            'in'       => 'The selected status is invalid.',
        ],
        'schedule_type' => [
            'required' => 'Schedule type is required.',
            'integer'  => 'Schedule type must be an integer.',
            'in'       => 'The selected schedule type is invalid.',
        ],
        'options' => [
            'required' => 'The options field is required.',
            'array' => 'Options must be an array.',
            'name' => [
                'string' => 'Option name must be a string.',
                'max' => 'Option name may not be greater than 255 characters.',
                'required_if' => 'Option name is required when option is active.',
            ],
            'price' => [
                'regex' => 'Option price must be a number.',
                'numeric' => 'Option price must be numeric.',
                'min' => 'Option price must be at least 0.',
                'max' => 'Option price may not be greater than 999999.',
                'required_if' => 'Option price is required when option is active.',
            ],
            'type' => [
                'required' => 'Option type is required.',
                'in' => 'Option type must be 1 or 2.',
            ],
            'user_type' => [
                'required_if' => 'Option user type is required when option is active.',
                'in' => 'Option user type must be 1, 2, or 3.',
            ],
            'unit' => [
                'required' => 'Option unit is required.',
                'regex' => 'Option unit must be a number.',
                'numeric' => 'Option unit must be numeric.',
                'min' => 'Option unit must be at least 0.',
                'max' => 'Option unit may not be greater than 4.',
            ],
            'is_active' => [
                'required' => 'Option active status is required.',
                'in' => 'Option active status must be 0 or 1.',
            ],
        ],
        'end_date' => [
            'required'         => 'End date is required.',
            'date_format'      => 'End date must be in Y-m-d format.',
            'after_or_equal'   => 'End date must be after or equal to start date.',
        ],
        'file' => [
            'required' => 'File is required.',
            'file'     => 'The upload must be a valid file.',
            'mimes'    => 'The file must be a CSV or TXT file.',
        ],
    ]
];
