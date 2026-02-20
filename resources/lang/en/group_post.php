<?php

return [
    'create' => [
        'success' => 'Group post created successfully',
        'failed' => 'Failed to create news'
    ],
    'update' => [
        'success' => 'Group post updated successfully',
        'failed' => 'Failed to update news'
    ],
    'delete' => [
        'success' => 'Group post deleted successfully',
        'failed' => 'Failed to delete news'
    ],
    'list' => [
        'message' => 'Group post list',
    ],
    'find' => [
        'message' => 'Group post detail',
    ],
    'import' => [
        'success' => 'Group post imported successfully',
        'failed' => 'Failed to import news'
    ],
    'not_found' => 'Group post not found',
    'toggle' => [
        'success' => 'Post reaction updated successfully.',
        'failed'  => 'Failed to update post reaction.',
    ],
    'validation' => [
        'id' => [
            'required' => 'ID is required',
            'integer' => 'ID must be an integer',
            'exists' => 'Group post not found'
        ],
        'remove_thumbnail' => [
            'boolean' => 'Remove thumbnail must be a boolean'
        ],
        'reaction_code' => [
            'required' => 'Reaction code is required.',
            'string'   => 'Reaction code must be a string.',
            'max'  => 'Reaction code may not be greater than 50 characters.',
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
        'file' => [
            'required' => 'File is required',
            'file' => 'File must be a valid file',
            'mimes' => 'File must be a CSV file',
            'max' => 'File size must not exceed 10GB'
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
            'news_not_found' => 'Group post not found or deleted',
            'category_string' => 'Category must be a string',
            'category_max' => 'Category must not exceed 255 characters',
            'tag_string' => 'Tag must be a string',
            'tag_max' => 'Tag must not exceed 255 characters',
            'empty_line' => 'Line cannot be completely empty',
            'category_create_failed' => 'Failed to create category',
            'tag_create_failed' => 'Failed to create tag',
        ],
        'group_id' => [
            'required' => 'Group ID is required',
            'integer' => 'Group ID must be an integer',
            'exists' => 'Group not found'
        ]
    ]
];
