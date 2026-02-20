<?php

return [
    [
        'name' => 'news_create',
        'description' => 'Create a news article. If category_id is not provided, the system will automatically select the most appropriate category based on the title and content. Use category_list tool to see available categories.',
        'domain' => 'news',
        'input_schema' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string', 'description' => 'The title of the news article'],
                'content' => ['type' => 'string', 'description' => 'The main content of the news article'],
                'short_description' => ['type' => 'string', 'description' => 'A brief summary of the article'],
                'category_id' => ['type' => 'integer', 'description' => 'Optional. The category ID. If not provided, will be auto-selected.'],
            ],
            'required' => ['title', 'content'],
        ]
    ]
];
