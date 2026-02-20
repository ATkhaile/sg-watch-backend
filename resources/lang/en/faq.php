<?php

return [
    'create' => [
        'success' => 'FAQ created successfully',
        'failed' => 'Failed to create FAQ'
    ],
    'update' => [
        'success' => 'FAQ updated successfully',
        'failed' => 'Failed to update FAQ'
    ],
    'delete' => [
        'success' => 'FAQ deleted successfully',
        'failed' => 'Failed to delete FAQ'
    ],
    'list' => [
        'message' => 'FAQ list',
    ],
    'find' => [
       'message' => 'FAQ detail',
    ],
    'not_found' => 'FAQ not found',
    'validation' => [
        'question' => [
            'required' => 'Question is required',
            'string' => 'Question must be a string',
            'max' => 'Question must not exceed 500 characters'
        ],
        'answer' => [
            'required' => 'Answer is required',
            'string' => 'Answer must be a string'
        ],
        'display_order' => [
            'integer' => 'Display order must be an integer',
            'min' => 'Display order must be at least 0'
        ],
        'is_published' => [
            'boolean' => 'Published status must be true or false'
        ],
        'search_question' => [
            'string' => 'Search question must be a string'
        ],
        'search_answer' => [
            'string' => 'Search answer must be a string'
        ],
        'filter_published' => [
            'boolean' => 'Filter published must be a boolean'
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1',
            'max' => 'Limit must not exceed 100'
        ],
        'sort_display_order' => [
            'in' => 'Sort display order must be asc or desc'
        ],
        'sort_created' => [
            'in' => 'Sort created must be asc or desc'
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be asc or desc'
        ],
    ]
];
