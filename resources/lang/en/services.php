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
            'required' => 'The service name is required.',
            'string' => 'The service name must be a string.',
            'max' => 'The service name may not be greater than 255 characters.',
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
        'message' => 'Services List',
    ],
    'create' => [
        'success' => 'Service created successfully.',
        'failed' => 'Failed to create service.',
    ],
    'find' => [
        'message' => 'Service detail',
    ],
    'update' => [
        'success' => 'Service updated successfully.',
        'failed' => 'Failed to update service.',
    ],
    'delete' => [
        'success' => 'Service deleted successfully.',
        'failed' => 'Failed to delete service.',
    ],
];
