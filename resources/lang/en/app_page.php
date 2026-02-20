<?php

return [
    'list' => [
        'message' => 'App page list retrieved successfully.',
    ],
    'create' => [
        'success' => 'App page created successfully.',
        'failed'  => 'Failed to create app page.',
    ],
    'find' => [
        'message' => 'App page details retrieved successfully.',
    ],
    'update' => [
        'success' => 'App page updated successfully.',
        'failed'  => 'Failed to update app page.',
    ],
    'delete' => [
        'success' => 'App page deleted successfully.',
        'failed'  => 'Failed to delete app page.',
    ],
    'validation' => [
        'name' => [
            'required' => 'Name is required.',
            'string'   => 'Name must be a string.',
            'max'      => 'Name may not be greater than 255 characters.',
        ],
        'index' => [
            'required' => 'Index is required.',
            'string'   => 'Index must be a string.',
            'max'      => 'Index may not be greater than 255 characters.',
            'unique'   => 'Index has already been taken.',
        ],
        'page' => [
            'integer' => 'Page must be an integer.',
            'min'     => 'Page must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'Limit must be an integer.',
            'min'     => 'Limit must be at least 1.',
        ],
        'sort' => [
            'string' => 'Sort must be a string.',
        ],
        'direction' => [
            'in' => 'Direction must be ASC or DESC.',
        ],
        'search' => [
            'string' => 'Search must be a string.',
            'max'    => 'Search may not be greater than 255 characters.',
        ],
        'id' => [
            'required' => 'ID is required.',
            'integer'  => 'ID must be an integer.',
            'exists'   => 'App page does not exist.',
        ],
    ],
];
