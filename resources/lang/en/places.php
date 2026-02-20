<?php

return [
    'validation' => [
        'code' => [
            'required' => 'The code field is required.',
            'string' => 'The code must be a string.',
            'max' => 'The code may not be greater than 255 characters.',
            'unique' => 'The code has already been taken.',
            'regex' => 'The code may only contain letters and numbers.',
        ],
        'name' => [
            'required' => 'The place name is required.',
            'string' => 'The place name must be a string.',
            'max' => 'The place name may not be greater than 255 characters.',
        ],
        'address' => [
            'string' => 'The address must be a string.',
            'max' => 'The address may not be greater than 10000 characters.',
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
        'message' => 'Places List',
    ],
    'create' => [
        'success' => 'Place created successfully.',
        'failed' => 'Failed to create place.',
    ],
    'find' => [
        'message' => 'Place detail',
    ],
    'update' => [
        'success' => 'Place updated successfully.',
        'failed' => 'Failed to update place.',
    ],
    'delete' => [
        'success' => 'Place deleted successfully.',
        'failed' => 'Failed to delete place.',
    ],
];
