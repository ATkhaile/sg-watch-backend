<?php

return [
    'create' => [
        'success' => 'News created successfully',
        'failed' => 'Failed to create news'
    ],
    'update' => [
        'success' => 'News updated successfully',
        'failed' => 'Failed to update news'
    ],
    'delete' => [
        'success' => 'News deleted successfully',
        'failed' => 'Failed to delete news'
    ],
    'list' => [
        'message' => 'News list',
    ],
    'detail' => [
        'message' => 'News detail',
    ],
    'import' => [
        'success' => 'News imported successfully',
        'failed' => 'Failed to import news',
        'job_created' => 'File uploaded successfully, import is pending processing'
    ],

    'not_found' => 'News not found',
    'validation' => [
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'News not found'
        ],
        'title' => [
            'required' => 'Title is required',
            'string' => 'Title must be a string',
            'max' => 'Title must not exceed 255 characters'
        ],
        'content' => [
            'required' => 'Content is required',
            'string' => 'Content must be a string'
        ],
        'category_id' => [
            'exists' => 'Category not found'
        ],
        'tag_ids' => [
            'array' => 'Tag IDs must be an array',
            'exists' => 'Tag not found'
        ],
        'search_title' => [
            'max' => 'Search title must not exceed 255 characters'
        ],
        'search_title_like' => [
            'boolean' => 'Search title like must be a boolean'
        ],
        'search_title_not' => [
            'boolean' => 'Search title not must be a boolean'
        ],
        'search_category' => [
            'exists' => 'Category not found'
        ],
        'search_category_like' => [
            'boolean' => 'Search category like must be a boolean'
        ],
        'search_category_not' => [
            'boolean' => 'Search category not must be a boolean'
        ],
        'search_tags' => [
            'exists' => 'Tag not found'
        ],
        'search_tags_like' => [
            'boolean' => 'Search tags like must be a boolean'
        ],
        'search_tags_not' => [
            'boolean' => 'Search tags not must be a boolean'
        ],
        'page' => [
            'integer' => 'Page must be an integer',
            'min' => 'Page must be at least 1'
        ],
        'limit' => [
            'integer' => 'Limit must be an integer',
            'min' => 'Limit must be at least 1'
        ],
        'sort_title' => [
            'in' => 'Sort title must be ASC or DESC'
        ],
        'sort_category' => [
            'in' => 'Sort category must be ASC or DESC'
        ],
        'sort_created' => [
            'in' => 'Sort created must be ASC or DESC'
        ],
        'sort_updated' => [
            'in' => 'Sort updated must be ASC or DESC'
        ],
        'import' => [
            'template_id_invalid' => 'Template ID must be a positive integer',
            'title_required' => 'The title field is required.',
            'title_string' => 'Title must be a string',
            'title_max' => 'Title must not exceed 255 characters',
            'body_required' => 'The body field is required.',
            'body_string' => 'Body must be a string',
            'body_max' => 'Body must not exceed 10000 characters',
            'status_required' => 'The status field is required.',
            'status_invalid' => 'Status must be 1 or 2',
            'news_not_found' => 'News not found or deleted',
            'category_string' => 'Category must be a string',
            'category_max' => 'Category must not exceed 255 characters',
            'tag_string' => 'Tag must be a string',
            'tag_max' => 'Tag must not exceed 255 characters',
            'empty_line' => 'Line cannot be completely empty',
            'category_create_failed' => 'Failed to create category',
            'tag_create_failed' => 'Failed to create tag',
        ],
        'description' => [
            'required' => 'A description is required',
            'string' => 'Please enter a description as a string',
            'max' => 'Please enter a description of 10,000 characters or less',
        ],
        'publish_date' => [
            'required' => 'A publication date is required',
            'date_format' => 'Please enter the publication date in Y/M/D format',
        ],
        'publish_flag' => [
            'required' => 'A publication flag is required',
            'boolean' => 'Please enter a boolean value for the publication flag',
        ],
    ]
];
