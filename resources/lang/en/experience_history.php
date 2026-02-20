<?php

return [
    'list' => [
        'message' => 'Experience history list retrieved successfully.',
    ],
    'create' => [
        'success' => 'Experience history created successfully.',
        'failed' => 'Failed to create experience history.',
    ],
    'update' => [
        'success' => 'Experience history updated successfully.',
        'failed' => 'Failed to update experience history.',
    ],
    'find' => [
        'message' => 'Experience history details retrieved successfully.',
    ],
    'delete' => [
        'success' => 'Experience history deleted successfully.',
        'failed' => 'Failed to delete experience history.',
    ],
    'validation' => [
        'page' => [
            'integer' => 'Page must be an integer.',
            'min' => 'Page must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer.',
            'min' => 'Limit must be at least 1.',
        ],
        'search' => [
            'string' => 'Search must be a string.',
            'max' => 'Search may not be greater than 255 characters.',
        ],
        'sort' => [
            'string' => 'Sort must be a string.',
        ],
        'direction' => [
            'in' => 'Direction must be ASC or DESC.',
        ],
        'experience' => [
            'required' => 'Experience is required.',
            'numeric' => 'Experience must be a number.',
        ],
        'memo' => [
            'string' => 'Memo must be a string.',
            'max' => 'Memo may not be greater than 1000 characters.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer' => 'ID must be an integer.',
            'exists' => 'The selected ID does not exist.',
        ],
        'user_id' => [
            'required' => 'User ID is required.',
            'integer' => 'User ID must be an integer.',
            'exists' => 'The selected User ID does not exist.',
        ],
    ],
];
