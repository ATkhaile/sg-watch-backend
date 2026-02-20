<?php

return [
    [
        'name' => 'category_create',
        'description' => 'Create a category for news',
        'domain' => 'category',
        'input_schema' => [
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string'],
                'description' => ['type' => 'string']
            ],
            'required' => ['name'],
        ]
    ],
    [
        'name' => 'category_list',
        'description' => 'Get all available categories for news articles. Returns a list of categories with their id, name, and description.',
        'domain' => 'category',
        'input_schema' => [
            'type' => 'object',
            'properties' => new \stdClass(),
            'required' => new \stdClass(),
        ]
    ]
];
