<?php

return [
    'create' => [
        'success' => 'Tags created successfully',
        'failed' => 'Failed to create tags'
    ],
    'update' => [
        'success' => 'Tags updated successfully',
        'failed' => 'Failed to update tags'
    ],
    'delete' => [
        'success' => 'Tags deleted successfully',
        'failed' => 'Failed to delete tags'
    ],
    'list' => [
        'message' => 'Tags list',
    ],
    'find' => [
       'message' => 'Tags detail',
    ],
    'not_found' => 'Tags not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'Tags not found'
        ],
        'name' => [
            'required' => 'Name is required',
            'string' => 'Name must be a string',
            'max' => 'Name must not exceed 255 characters'
        ],
        'search_name' => [
            'max' => 'Search title must not exceed 255 characters'
        ],
        'search_name_like' => [
            'boolean' => 'Search title like must be a boolean'
        ],
        'search_name_not' => [
            'boolean' => 'Search title not must be a boolean'
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
       'sort_name' => [
            'in' => 'Sort name must be ASC or DESC'
        ],
       'sort_created' => [
            'in' => 'Sort created must be ASC or DESC'
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be ASC or DESC'
        ]
    ]
];
