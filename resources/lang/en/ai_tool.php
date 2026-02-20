<?php

return [
    'list' => [
        'message' => 'AI tools list retrieved successfully',
    ],
    'not_found' => 'AI tool not found',
    'report_retrieved' => 'AI tool report retrieved successfully',
    'update' => [
        'success' => 'AI tool updated successfully',
        'failed' => 'Failed to update AI tool',
    ],
    'toggle_active' => [
        'success' => 'AI tool status changed successfully',
        'failed' => 'Failed to change AI tool status',
    ],
    'validation' => [
        'search_name' => [
            'max' => 'Search name must be less than 256 characters',
        ],
        'search_name_like' => [
            'boolean' => 'Search name like must be a boolean',
        ],
        'search_active' => [
            'boolean' => 'Active status must be a boolean',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
        ],
        'sort_name' => [
            'in' => 'Sort name must be ASC or DESC',
        ],
        'sort_created' => [
            'in' => 'Sort created must be ASC or DESC',
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be ASC or DESC',
        ],
        'start_date' => [
            'date' => 'Start date must be a valid date',
            'date_format' => 'Start date must be in Y-m-d format',
        ],
        'end_date' => [
            'date' => 'End date must be a valid date',
            'date_format' => 'End date must be in Y-m-d format',
            'after_or_equal' => 'End date must be after or equal to start date',
        ],
        'conversations_page' => [
            'integer' => 'Conversations page must be an integer',
            'min' => 'Conversations page must be at least 1',
        ],
        'conversations_limit' => [
            'integer' => 'Conversations limit must be an integer',
            'min' => 'Conversations limit must be at least 1',
            'max' => 'Conversations limit must be at most 100',
        ],
        'sample_io_page' => [
            'integer' => 'Sample IO page must be an integer',
            'min' => 'Sample IO page must be at least 1',
        ],
        'sample_io_limit' => [
            'integer' => 'Sample IO limit must be an integer',
            'min' => 'Sample IO limit must be at least 1',
            'max' => 'Sample IO limit must be at most 50',
        ],
    ],
];
