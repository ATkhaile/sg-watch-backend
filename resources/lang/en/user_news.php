<?php

return [
    'list' => [
        'message' => 'User news list retrieved successfully.',
    ],
    'detail' => [
        'message' => 'User news detail retrieved successfully.',
    ],
    'top' => [
        'message' => 'User top news retrieved successfully.',
    ],
    'validation' => [
        'id' => [
            'required' => 'The news ID field is required.',
            'integer'  => 'The news ID must be an integer.',
            'exists'   => 'The specified news does not exist.',
        ],
    ],
];