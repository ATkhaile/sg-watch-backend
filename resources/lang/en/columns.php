<?php

return [
    'create' => [
        'success' => 'Columns created successfully',
        'failed' => 'Failed to create columns'
    ],
    'update' => [
        'success' => 'Columns updated successfully',
        'failed' => 'Failed to update columns'
    ],
    'delete' => [
        'success' => 'Columns deleted successfully',
        'failed' => 'Failed to delete columns'
    ],
    'list' => [
        'message' => 'Columns list',
    ],
    'find' => [
       'message' => 'Columns detail',
    ],
    'checkout' => [
        'success' => 'Checkout session created successfully',
        'failed' => 'Failed to create checkout session'
    ],
    'not_found' => 'Columns not found',
    'validation' => [
        'id' => [
           'required' => 'ID is required',
           'integer' => 'ID must be an integer',
           'exists' => 'Columns not found'
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
        'stripe_product_id' => [
            'string' => 'Stripe product ID must be a string',
            'max' => 'Stripe product ID must not exceed 255 characters'
        ],
        'stripe_price_id' => [
            'string' => 'Stripe price ID must be a string',
            'max' => 'Stripe price ID must not exceed 255 characters'
        ],
        'stripe_account_id' => [
            'exists' => 'Stripe account not found'
        ],
        'image' => [
            'image' => 'Image must be an image file',
            'mimes' => 'Image must be jpeg, jpg, png, gif, or webp format',
            'max' => 'Image must not exceed 10MB'
        ]
    ]
];
