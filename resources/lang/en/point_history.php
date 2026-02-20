<?php

return [
    'list' => [
        'message' => 'Point history list retrieved successfully.',
    ],
    'create' => [
        'success' => 'Point history created successfully.',
        'failed' => 'Failed to create point history.',
    ],
    'find' => [
        'message' => 'Point history details retrieved successfully.',
    ],
    'update' => [
        'success' => 'Point history updated successfully.',
        'failed' => 'Failed to update point history.',
    ],
    'delete' => [
        'success' => 'Point history deleted successfully.',
        'failed' => 'Failed to delete point history.',
    ],
    'validation' => [
        'user_id' => [
            'required' => 'User ID is required.',
            'integer' => 'User ID must be an integer.',
            'exists' => 'User does not exist.',
        ],
        'point' => [
            'required' => 'Point is required.',
            'integer' => 'Point must be an integer.',
            'min' => 'Point must be at least 0.',
            'max' => 'Point may not be greater than 999999999999.',
        ],
        'memo' => [
            'string' => 'Memo must be a string.',
            'max' => 'Memo may not be greater than 1000 characters.',
        ],
        'page' => [
            'integer' => 'Page must be an integer.',
            'min' => 'Page must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer.',
            'min' => 'Limit must be at least 1.',
        ],
        'sort' => [
            'string' => 'Sort must be a string.',
        ],
        'direction' => [
            'in' => 'Direction must be ASC or DESC.',
        ],
        'search' => [
            'string' => 'Search must be a string.',
            'max' => 'Search may not be greater than 255 characters.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer' => 'ID must be an integer.',
            'exists' => 'Point history does not exist.',
        ],
    ],
];
