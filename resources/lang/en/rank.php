<?php

return [
    'validation' => [
        'page' => [
            'integer' => 'The page must be an integer.',
            'min' => 'The page must be at least 1.',
        ],
        'limit' => [
            'integer' => 'The limit must be an integer.',
            'min' => 'The limit must be at least 1.',
        ],
        'per_page' => [
            'integer' => 'The per page must be an integer.',
            'min' => 'The per page must be at least 1.',
            'max' => 'The per page may not be greater than 100.',
        ],
        'search' => [
            'string' => 'The search must be a string.',
            'max' => 'The search may not be greater than 255 characters.',
        ],
        'sort' => [
            'string' => 'The sort must be a string.',
            'in' => 'The selected sort field is invalid.',
        ],
        'direction' => [
            'in' => 'The direction must be either ASC or DESC.',
        ],
        'id' => [
            'required' => 'The ID is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
        'title' => [
            'required' => 'The title is required.',
            'string' => 'The title must be a string.',
            'max' => 'The title may not be greater than 255 characters.',
        ],
        'min_rank' => [
            'required' => 'The minimum rank is required.',
            'integer' => 'The minimum rank must be an integer.',
            'min' => 'The minimum rank must be at least 0.',
            'max' => 'The minimum rank may not be greater than 9999999999.',
        ],
        'max_rank' => [
            'required' => 'The maximum rank is required.',
            'integer' => 'The maximum rank must be an integer.',
            'min' => 'The maximum rank must be at least 0.',
            'max' => 'The maximum rank may not be greater than 9999999999.',
            'gte' => 'The maximum rank must be greater than or equal to minimum rank.',
        ],
    ],
    'list' => [
        'message' => 'Rank Masters List',
    ],
    'find' => [
        'message' => 'Rank Master detail',
    ],
    'create' => [
        'success' => 'Rank Master created successfully.',
        'failed' => 'Failed to create Rank Master.',
    ],
    'update' => [
        'success' => 'Rank Master updated successfully.',
        'failed' => 'Failed to update Rank Master.',
    ],
    'delete' => [
        'success' => 'Rank Master deleted successfully.',
        'failed' => 'Failed to delete Rank Master.',
    ],
     'new_point' => [
        'message' => 'New points calculated successfully',
    ],
];