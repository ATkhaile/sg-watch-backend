<?php

return [
    'find' => [
        'message' => 'AI provider retrieved successfully.',
    ],
    'models' => [
        'message' => 'Available models retrieved successfully.',
    ],
    'create' => [
        'success' => 'AI provider created successfully.',
        'failed' => 'Failed to create AI provider.',
    ],
    'update' => [
        'success' => 'AI provider updated successfully.',
        'failed' => 'Failed to update AI provider.',
    ],
    'delete' => [
        'success' => 'AI provider deleted successfully.',
        'failed' => 'Failed to delete AI provider.',
    ],
    'validation' => [
        'id' => [
            'required' => 'ID is required.',
            'integer' => 'ID must be an integer.',
            'exists' => 'The specified AI provider does not exist.',
        ],
        'name' => [
            'required' => 'Name is required.',
            'string' => 'Name must be a string.',
            'max' => 'Name must not exceed 255 characters.',
        ],
        'api_key' => [
            'required' => 'API key is required.',
            'string' => 'API key must be a string.',
            'max' => 'API key must not exceed 1024 characters.',
        ],
        'active' => [
            'integer' => 'Active must be an integer.',
        ],
        'base_url' => [
            'string' => 'Base URL must be a string.',
            'max' => 'Base URL must not exceed 512 characters.',
        ],
        'provider' => [
            'required' => 'Provider is required.',
            'string' => 'Provider must be a string.',
            'max' => 'Provider must not exceed 64 characters.',
        ],
        'quota_limit' => [
            'integer' => 'Quota limit must be an integer.',
            'min' => 'Quota limit must be at least 0.',
        ],
        'quota_used' => [
            'integer' => 'Quota used must be an integer.',
            'min' => 'Quota used must be at least 0.',
        ],
        'search' => [
            'max' => 'Search keyword must not exceed 256 characters.',
        ],
        'page' => [
            'integer' => 'Page must be an integer.',
            'min' => 'Page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer.',
            'min' => 'Limit must be at least 1.',
        ],
        'sort' => [
            'in' => 'Sort must be a valid value.',
        ],
        'direction' => [
            'in' => 'Direction must be a valid value.',
        ],
    ],
];
