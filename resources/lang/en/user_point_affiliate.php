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
        'user_id' => [
            'required' => 'The user ID is required.',
            'integer' => 'The user ID must be an integer.',
            'exists' => 'The selected user ID is invalid.',
        ],
        'start_date' => [
            'required' => 'The start date is required.',
            'date_format' => 'The start date format is invalid.',
        ],
        'end_date' => [
            'date_format' => 'The end date format is invalid.',
            'after_or_equal' => 'The end date must be after or equal to start date.',
        ],
        'point' => [
            'required' => 'The point is required.',
            'integer' => 'The point must be an integer.',
            'min' => 'The point must be at least 0.',
            'max' => 'The point may not be greater than 999999999999.',
        ],
        'status' => [
            'in' => 'The selected status is invalid.',
        ],
        'id' => [
            'required' => 'The ID is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
    ],
    'list' => [
        'message' => 'User Point Affiliate List',
    ],
    'find' => [
        'message' => 'User Point Affiliate detail',
    ],
    'save' => [
        'success' => 'User Point Affiliate created successfully.',
        'failed' => 'Failed to create User Point Affiliate.',
    ],
    'update' => [
        'success' => 'User Point Affiliate updated successfully.',
        'failed' => 'Failed to update User Point Affiliate.',
    ],
    'delete' => [
        'success' => 'User Point Affiliate deleted successfully.',
        'failed' => 'Failed to delete User Point Affiliate.',
    ],
];