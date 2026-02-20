<?php

return [
    'create' => [
        'success' => 'Dify created successfully',
        'failed' => 'Failed to create plans'
    ],
    'update' => [
        'success' => 'Dify updated successfully',
        'failed' => 'Failed to update plans'
    ],
    'delete' => [
        'success' => 'Dify deleted successfully',
        'failed' => 'Failed to delete plans'
    ],
    'list' => [
        'message' => 'Dify list',
    ],
    'find' => [
       'message' => 'Dify detail',
    ],
    'not_found' => 'Dify not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'ID not found'
        ],
        'user_id' => [
            'required' => 'user is required',
            'integer' => 'user must be an integer',
            'exists' => 'user not found',
            'unique' => 'user must be unique',
        ],
        'base_url' => [
            'required' => 'base_url is required',
            'string' => 'base_url must be a string',
        ],
        'app_key' => [
            'required' => 'app_key is required',
            'string' => 'app_key must be a string',
        ],
        'search_user_id' => [
            'max' => 'Search user_id must not exceed 256 characters'
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
       'sort_user_id' => [
            'in' => 'Sort user_id must be ASC or DESC'
        ],
       'sort_created' => [
            'in' => 'Sort created must be ASC or DESC'
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be ASC or DESC'
        ]
    ]
];
