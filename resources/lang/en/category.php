<?php

return [
    'create' => [
        'success' => 'Category created successfully',
        'failed' => 'Failed to create category'
    ],
    'update' => [
        'success' => 'Category updated successfully',
        'failed' => 'Failed to update category'
    ],
    'delete' => [
        'success' => 'Category deleted successfully',
        'failed' => 'Failed to delete category'
    ],
    'list' => [
        'message' => 'Category list',
    ],
    'find' => [
       'message' => 'Category detail',
    ],
    'not_found' => 'Category not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'Category not found'
        ],
        'name' => [
            'required' => 'Name is required',
            'string' => 'Name must be a string',
            'max' => 'Name must not exceed 255 characters'
        ],
        'description' => [
           'string' => 'Description must be a string',
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
