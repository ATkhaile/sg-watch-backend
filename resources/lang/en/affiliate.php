<?php

return [
    'list' => [
        'message' => 'Affiliate list fetched successfully.',
    ],
    'create' => [
        'success' => 'Affiliate created successfully.',
        'failed' => 'Failed to create affiliate.',
    ],
    'delete' => [
        'success' => 'Affiliate deleted successfully.',
        'failed' => 'Failed to delete affiliate.',
    ],
    'validation' => [
        'page' => [
            'integer' => 'Page must be an integer.',
            'min' => 'Page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer.',
            'min' => 'Limit must be at least 1.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer' => 'ID must be an integer.',
            'exists' => 'The specified affiliate does not exist or has been deleted.',
        ],
        'redirect_base_url' => [
            'required' => 'Redirect base URL is required.',
            'url' => 'Redirect base URL must be a valid URL.',
            'max' => 'Redirect base URL may not be greater than 500 characters.',
        ],
    ],
];
