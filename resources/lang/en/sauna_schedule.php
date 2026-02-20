<?php

return [
    'import' => [
        'success' => 'Schedule imported successfully',
        'failed'  => 'Failed to import schedule',
        'invalid_file' => 'The uploaded file is invalid or has an incorrect format.',
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
        'date' => [
            'required'       => 'Date is required.',
            'date_format'    => 'Date must be in Y-m-d format.',
            'after_or_equal' => 'Date must be today or in the future.',
        ],
        'status' => [
            'required' => 'Status is required.',
            'integer'  => 'Status must be an integer.',
        ],
        'day_trip_normal_price' => [
            'required' => 'Day trip normal price is required.',
            'numeric'  => 'Day trip normal price must be numeric.',
            'min'      => 'Day trip normal price must be at least 50.',
            'max'      => 'Day trip normal price may not be greater than 999999.',
        ],
        'stay_normal_price' => [
            'required' => 'Stay normal price is required.',
            'numeric'  => 'Stay normal price must be numeric.',
            'min'      => 'Stay normal price must be at least 50.',
            'max'      => 'Stay normal price may not be greater than 999999.',
        ],
        'parking_flag' => [
            'required' => 'Parking flag is required.',
            'in'       => 'Parking flag must be 0 (none) or 1 (available).',
        ],
        'parking_price' => [
            'required_if' => 'Parking price is required when parking is available.',
            'numeric'     => 'Parking price must be numeric.',
            'min'         => 'Parking price must be at least 0.',
            'max'         => 'Parking price may not be greater than 999999.',
        ],
        'options' => [
            'array' => 'Options must be an array.',
            'name' => [
                'string'      => 'Option name must be a string.',
                'max'         => 'Option name may not be greater than 255 characters.',
                'required_if' => 'Option name is required when option is active.',
            ],
            'price' => [
                'regex'       => 'Option price must be a number.',
                'numeric'     => 'Option price must be numeric.',
                'min'         => 'Option price must be at least 0.',
                'max'         => 'Option price may not be greater than 999999.',
                'required_if' => 'Option price is required when option is active.',
            ],
            'type' => [
                'required' => 'Option type is required.',
                'in'       => 'Option type must be 1 or 2.',
            ],
            'unit' => [
                'required' => 'Option unit is required.',
                'regex'    => 'Option unit must be a number.',
                'numeric'  => 'Option unit must be numeric.',
                'min'      => 'Option unit must be at least 0.',
                'max'      => 'Option unit may not be greater than 4.'
            ],
            'is_active' => [
                'required' => 'Option active status is required.',
                'in'       => 'Option active status must be 0 or 1.',
            ],
        ],
        'file' => [
            'required' => 'File is required.',
            'file'     => 'The upload must be a valid file.',
            'mimes'    => 'The file must be a CSV or TXT file.',
        ],
    ],
];
