<?php

return [
    'list' => [
        'message' => 'Location list',
    ],
    'detail' => [
        'message' => 'Location detail',
    ],
    'not_found' => 'Location not found',
    'validation' => [
        'id' => [
            'required' => 'Location ID is required',
            'integer' => 'Location ID must be an integer',
            'exists' => 'Location does not exist',
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
    ]
];
