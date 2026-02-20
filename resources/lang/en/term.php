<?php

return [
    'update' => [
        'success' => 'Term updated successfully',
        'failed' => 'Failed to update term'
    ],
    'detail' => [
        'message' => 'Term detail'
    ],
    'validation' => [
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
        ],
        'shop_id' => [
            'required' => 'Shop ID is required',
            'integer' => 'Shop ID must be an integer',
        ],
        'content' => [
            'required' => 'Content is required',
            'string' => 'Content must be a string',
        ],
    ]
];
