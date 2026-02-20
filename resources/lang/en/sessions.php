<?php

return [
    'create' => [
        'success' => 'Session created successfully',
        'failed' => 'Failed to create session'
    ],
    'update' => [
        'success' => 'Session updated successfully',
        'failed' => 'Failed to update session'
    ],
    'delete' => [
        'success' => 'Session deleted successfully',
        'failed' => 'Failed to delete session'
    ],
    'list' => [
        'message' => 'Session list',
    ],
    'find' => [
       'message' => 'Session detail',
    ],
    'not_found' => 'Session not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'Session not found'
        ],
        'user_id' => [
            'integer' => 'User ID must be an integer',
            'exists' => 'User does not exist'
        ],
        'search_user_id' => [
            'exists' => 'Category not found'
        ],

        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be greater than 0'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be greater than 0'
        ],
        'sort_created' => [
            'in' => 'Invalid sort direction for created date'
        ],
        'sort_updated' => [
            'in' => 'Invalid sort direction for updated date'
        ]
    ]
];
