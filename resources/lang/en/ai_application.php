<?php

return [
    'find' => [
        'message' => 'AI application retrieved successfully.',
    ],
    'create' => [
        'success' => 'AI application created successfully.',
        'failed' => 'Failed to create AI application.',
    ],
    'update' => [
        'success' => 'AI application updated successfully.',
        'failed' => 'Failed to update AI application.',
    ],
    'delete' => [
        'success' => 'AI application deleted successfully.',
        'failed' => 'Failed to delete AI application.',
    ],
    'toggle' => [
        'activated' => 'AI application activated successfully.',
        'deactivated' => 'AI application deactivated successfully.',
        'failed' => 'Failed to change status.',
    ],
    'generate_api_key' => [
        'success' => 'API key generated successfully.',
        'failed' => 'Failed to generate API key.',
    ],
    'copy' => [
        'success' => 'AI application copied successfully.',
        'failed' => 'Failed to copy AI application.',
    ],
    'validation' => [
        'active' => [
            'required' => 'Active status is required.',
            'boolean' => 'Active status must be a boolean.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer' => 'ID must be an integer.',
            'exists' => 'The specified AI application does not exist.',
        ],
        'name' => [
            'required' => 'Name is required.',
            'string' => 'Name must be a string.',
            'max' => 'Name must not exceed 255 characters.',
        ],
        'provider_id' => [
            'required' => 'Provider is required.',
            'integer' => 'Provider ID must be an integer.',
            'exists' => 'The specified provider does not exist.',
        ],
        'model' => [
            'required' => 'Model is required.',
            'string' => 'Model must be a string.',
            'max' => 'Model must not exceed 255 characters.',
        ],
        'temperature' => [
            'numeric' => 'Temperature must be a number.',
            'min' => 'Temperature must be at least 0.',
            'max' => 'Temperature must not exceed 2.',
        ],
        'max_tokens' => [
            'integer' => 'Max tokens must be an integer.',
            'min' => 'Max tokens must be at least 1.',
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
