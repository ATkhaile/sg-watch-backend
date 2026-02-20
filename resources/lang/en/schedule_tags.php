<?php

return [
    'validation' => [
        'name' => [
            'required' => 'The tag name is required.',
            'string' => 'The tag name must be a string.',
            'max' => 'The tag name may not be greater than 255 characters.',
        ],
        'search' => [
            'string' => 'The search must be a string.',
            'max' => 'The search may not be greater than 255 characters.',
        ],
        'direction' => [
            'in' => 'The direction must be either ASC or DESC.',
        ],
        'sort' => [
            'string' => 'The sort must be a string.',
            'in' => 'The selected sort field is invalid.',
        ],
        'id' => [
            'required' => 'The ID is required.',
            'integer' => 'The ID must be an integer.',
            'exists' => 'The selected ID is invalid.',
        ],
    ],
    'list' => [
        'message' => 'Schedule Tags List',
    ],
    'create' => [
        'success' => 'Tag created successfully.',
        'failed' => 'Failed to create tag.',
    ],
    'find' => [
        'message' => 'Tag detail',
    ],
    'update' => [
        'success' => 'Tag updated successfully.',
        'failed' => 'Failed to update tag.',
    ],
    'delete' => [
        'success' => 'Tag deleted successfully.',
        'failed' => 'Failed to delete tag.',
    ],
];
