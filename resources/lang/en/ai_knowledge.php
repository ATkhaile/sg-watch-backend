<?php

return [
    'create' => [
        'success' => 'AI knowledge created successfully',
        'failed' => 'Failed to create AI knowledge'
    ],
    'update' => [
        'success' => 'AI knowledge updated successfully',
        'failed' => 'Failed to update AI knowledge'
    ],
    'delete' => [
        'success' => 'AI knowledge deleted successfully',
        'failed' => 'Failed to delete AI knowledge'
    ],
    'list' => [
        'message' => 'AI knowledge list',
    ],
    'find' => [
        'message' => 'AI knowledge detail',
    ],
    'not_found' => 'AI knowledge not found',
    'validation' => [
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'AI knowledge not found'
        ],
        'title' => [
            'string' => 'Title must be a string',
            'max' => 'Title must not exceed 255 characters'
        ],
        'description' => [
            'string' => 'Description must be a string',
            'max' => 'Description must not exceed 5000 characters'
        ],
        'file' => [
            'required' => 'File is required',
            'file' => 'File must be a valid file',
            'max' => 'File size must not exceed 100MB',
        ],
        'search' => [
            'string' => 'Search must be a string',
            'max' => 'Search must not exceed 255 characters',
        ],
        'file_type' => [
            'in' => 'Invalid file type',
        ],
        'is_embedded' => [
            'in' => 'Invalid is_embedded value',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
            'max' => 'Limit must not exceed 100',
        ],
        'sort_by' => [
            'in' => 'Invalid sort field',
        ],
        'sort_order' => [
            'in' => 'Sort order must be asc or desc',
        ],
    ],
];
