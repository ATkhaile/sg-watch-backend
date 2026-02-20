<?php
return [
    'validation' => [
        'product_id' => [
            'required' => 'product_id is required.',
            'string'   => 'product_id must be a string.',
            'max'      => 'product_id must not exceed 50 characters.',
            'exists'   => 'product_id does not exist or has been deleted.',
        ],

        'control_flag' => [
            'required'=>'control_flag is required.',
            'string'=>'control_flag must be a string.',
            'max'=>'control_flag must not exceed 50 characters.',
        ],
        'publish_status' => [
            'required'=>'publish_status is required.',
            'in'=>'publish_status value is invalid.',
        ],
        'publish_start_date' => [
            'required'=>'publish_start_date is required.',
            'date_format'=>'publish_start_date must match format Y-m-d H:i:s.',
        ],
        'publish_end_date' => [
            'date_format'=>'publish_end_date must match format Y-m-d H:i:s.',
        ],
        'product_name' => [
            'required'=>'product_name is required.',
            'string'=>'product_name must be a string.',
            'max'=>'product_name must not exceed 255 characters.',
        ],
        'short_description_mobile' => [
            'required'=>'short_description_mobile is required.',
            'string'=>'short_description_mobile must be a string.',
        ],
        'short_description' => [
            'required'=>'short_description is required.',
            'string'=>'short_description must be a string.',
        ],

        'stock_type' => [
            'required'=>'stock_type is required.',
            'in'=>'stock_type value is invalid.',
        ],
        'stock_quantity' => [
            'required'=>'stock_quantity is required.',
            'integer'=>'stock_quantity must be an integer.',
            'min'=>'stock_quantity must be at least 0.',
        ],
        'sale_price' => [
            'required'=>'sale_price is required.',
            'numeric'=>'sale_price must be a number.',
            'min'=>'sale_price must be at least 0.',
        ],
        'product_code' => [
            'required'=>'product_code is required.',
            'string'=>'product_code must be a string.',
            'max'=>'product_code must not exceed 50 characters.',
        ],
        'detail_description_mobile' => [
            'required'=>'detail_description_mobile is required.',
            'string'=>'detail_description_mobile must be a string.',
        ],
        'detail_description' => [
            'required'=>'detail_description is required.',
            'string'=>'detail_description must be a string.',
        ],
        'list_price' => [
            'required'=>'list_price is required.',
            'numeric'=>'list_price must be a number.',
            'min'=>'list_price must be at least 0.',
        ],

        'stock_display_setting' => [
            'required'=>'stock_display_setting is required.',
            'in'=>'stock_display_setting value is invalid.',
        ],
        'low_stock_threshold' => [
            'required'=>'low_stock_threshold is required.',
            'integer'=>'low_stock_threshold must be an integer.',
            'min'=>'low_stock_threshold must be at least 0.',
        ],
        'soldout_setting' => [
            'required'=>'soldout_setting is required.',
            'in'=>'soldout_setting value is invalid.',
        ],
        'shipping_type' => [
            'required'=>'shipping_type is required.',
            'in'=>'shipping_type value is invalid.',
        ],
        'delivery_type' => [
            'required'=>'delivery_type is required.',
            'string'=>'delivery_type must be a string.',
            'max'=>'delivery_type must not exceed 100 characters.',
        ],
        'individual_shipping_fee' => [
            'required'=>'individual_shipping_fee is required.',
            'numeric'=>'individual_shipping_fee must be a number.',
            'min'=>'individual_shipping_fee must be at least 0.',
        ],

        'min_purchase_limit' => [
            'required'=>'min_purchase_limit is required.',
            'integer'=>'min_purchase_limit must be an integer.',
            'min'=>'min_purchase_limit must be at least 0.',
        ],
        'max_purchase_limit' => [
            'required'=>'max_purchase_limit is required.',
            'integer'=>'max_purchase_limit must be an integer.',
            'min'=>'max_purchase_limit must be at least 0.',
        ],
        'pr_setting' => [
            'required'=>'pr_setting is required.',
            'boolean'=>'pr_setting must be true or false.',
        ],
        'review_setting' => [
            'required'=>'review_setting is required.',
            'boolean'=>'review_setting must be true or false.',
        ],
        'bulk_purchase_setting' => [
            'required'=>'bulk_purchase_setting is required.',
            'boolean'=>'bulk_purchase_setting must be true or false.',
        ],

        'sale_period_enable' => [
            'required'=>'sale_period_enable is required.',
            'boolean'=>'sale_period_enable must be true or false.',
        ],
        'sale_start_datetime' => [
            'date_format'=>'sale_start_datetime must match format Y-m-d H:i:s.',
        ],
        'sale_end_datetime' => [
            'date_format'=>'sale_end_datetime must match format Y-m-d H:i:s.',
        ],
        'sale_period_display' => [
            'required'=>'sale_period_display is required.',
            'boolean'=>'sale_period_display must be true or false.',
        ],

        'access_limit' => [
            'required'=>'access_limit is required.',
            'in'=>'access_limit value is invalid.',
        ],
        'display_priority' => [
            'required'=>'display_priority is required.',
            'integer'=>'display_priority must be an integer.',
        ],
        'weight' => [
            'required'=>'weight is required.',
            'numeric'=>'weight must be a number.',
            'min'=>'weight must be at least 0.',
        ],

        'thumbnail_url' => [
            'required'=>'thumbnail_url is required.',
            'string'=>'thumbnail_url must be a string.',
            'max'=>'thumbnail_url must not exceed 255 characters.',
        ],
        'image_url' => [
            'required'=>'image_url is required.',
            'string'=>'image_url must be a string.',
            'max'=>'image_url must not exceed 255 characters.',
        ],
        'image_description' => [
            'required'=>'image_description is required.',
            'string'=>'image_description must be a string.',
            'max'=>'image_description must not exceed 255 characters.',
        ],
        'page_title' => [
            'required'=>'page_title is required.',
            'string'=>'page_title must be a string.',
            'max'=>'page_title must not exceed 255 characters.',
        ],
        'keywords' => [
            'required'=>'keywords is required.',
            'string'=>'keywords must be a string.',
            'max'=>'keywords must not exceed 255 characters.',
        ],
        'description' => [
            'required'=>'description is required.',
            'string'=>'description must be a string.',
        ],
        'search_keyword_setting' => [
            'required'=>'search_keyword_setting is required.',
            'string'=>'search_keyword_setting must be a string.',
        ],
        'layout_type' => [
            'required'=>'layout_type is required.',
            'string'=>'layout_type must be a string.',
            'max'=>'layout_type must not exceed 100 characters.',
        ],
        'free_area_order' => [
            'string'=>'free_area_order must be a string.',
        ],
        'purchase_limit_count' => [
            'integer'=>'purchase_limit_count must be an integer.',
            'min'=>'purchase_limit_count must be at least 0.',
        ],
        'head_insert' => [
            'string'=>'head_insert must be a string.',
        ],

        'subscription_setting' => [
            'required'=>'subscription_setting is required.',
            'boolean'=>'subscription_setting must be true or false.',
        ],
        'mail_bin_limit' => [
            'required'=>'mail_bin_limit is required.',
            'integer'=>'mail_bin_limit must be an integer.',
            'min'=>'mail_bin_limit must be at least 0.',
        ],
        'hide_from_list' => [
            'required'=>'hide_from_list is required.',
            'boolean'=>'hide_from_list must be true or false.',
        ],
        'trial_product_flag' => [
            'required'=>'trial_product_flag is required.',
            'boolean'=>'trial_product_flag must be true or false.',
        ],
        'payment_disabled' => [
            'string'=>'payment_disabled must be a string.',
            'max'=>'payment_disabled must not exceed 255 characters.',
        ],

        'shipping_disabled' => [
            'required'=>'shipping_disabled is required.',
            'boolean'=>'shipping_disabled must be true or false.',
        ],
        'release_date' => [
            'required'=>'release_date is required.',
            'date_format'=>'release_date must match format Y-m-d.',
        ],
        'release_date_display' => [
            'required'=>'release_date_display is required.',
            'boolean'=>'release_date_display must be true or false.',
        ],
        'template_selection' => [
            'required'=>'template_selection is required.',
            'string'=>'template_selection must be a string.',
            'max'=>'template_selection must not exceed 100 characters.',
        ],
        'variation_price_enable' => [
            'required'=>'variation_price_enable is required.',
            'boolean'=>'variation_price_enable must be true or false.',
        ],
        'related_products' => [
            'string'=>'related_products must be a string.',
        ],

        'tax_category' => [
            'required'=>'tax_category is required.',
            'in'=>'tax_category value is invalid.',
        ],
        'image_title' => [
            'required'=>'image_title is required.',
            'string'=>'image_title must be a string.',
            'max'=>'image_title must not exceed 255 characters.',
        ],
        'google_feed_output' => [
            'required'=>'google_feed_output is required.',
            'boolean'=>'google_feed_output must be true or false.',
        ],
        'facebook_feed_output' => [
            'required'=>'facebook_feed_output is required.',
            'boolean'=>'facebook_feed_output must be true or false.',
        ],
        'google_product_category' => [
            'required'=>'google_product_category is required.',
            'string'=>'google_product_category must be a string.',
            'max'=>'google_product_category must not exceed 255 characters.',
        ],
        'jan_code' => [
            'required'=>'jan_code is required.',
            'string'=>'jan_code must be a string.',
            'max'=>'jan_code must not exceed 13 characters.',
        ],
        'color' => [
            'required'=>'color is required.',
            'string'=>'color must be a string.',
            'max'=>'color must not exceed 100 characters.',
        ],
        'brand' => [
            'required'=>'brand is required.',
            'string'=>'brand must be a string.',
            'max'=>'brand must not exceed 100 characters.',
        ],
        'size' => [
            'required'=>'size is required.',
            'string'=>'size must be a string.',
            'max'=>'size must not exceed 50 characters.',
        ],

        'product_option' => [
            'string'=>'product_option must be a string.',
        ],
        'common_option' => [
            'string'=>'common_option must be a string.',
        ],
        'product_sort_setting' => [
            'string'=>'product_sort_setting must be a string.',
        ],
        'social_gift_setting' => [
            'required'=>'social_gift_setting is required.',
            'boolean'=>'social_gift_setting must be true or false.',
        ],
        'delivery_pattern_setting' => [
            'required'=>'delivery_pattern_setting is required.',
            'string'=>'delivery_pattern_setting must be a string.',
            'max'=>'delivery_pattern_setting must not exceed 100 characters.',
        ],

        // options
        'option' => [
            'required' => 'option is required.',
            'array'    => 'option must be an array.',

            'id' => [
                'integer' => 'option id must be an integer.',
            ],
            'control_flag' => [
                'required' => 'option.control_flag is required.',
                'string'   => 'option.control_flag must be a string.',
                'max'      => 'option.control_flag must not exceed 50 characters.',
            ],
            'option_type' => [
                'required' => 'option.option_type is required.',
                'in'       => 'option.option_type value is invalid.',
            ],
            'option_name' => [
                'required' => 'option.option_name is required.',
                'string'   => 'option.option_name must be a string.',
                'max'      => 'option.option_name must not exceed 100 characters.',
            ],
            'option_choice_name' => [
                'string' => 'option.option_choice_name must be a string.',
                'max'    => 'option.option_choice_name must not exceed 100 characters.',
            ],
            'option_include_setting' => [
                'string' => 'option.option_include_setting must be a string.',
            ],
            'option_exclude_setting' => [
                'string' => 'option.option_exclude_setting must be a string.',
            ],
            'option_description' => [
                'string' => 'option.option_description must be a string.',
            ],
            'option_rate' => [
                'numeric' => 'option.option_rate must be a number.',
            ],
            'option_rate_type' => [
                'in' => 'option.option_rate_type value is invalid.',
            ],

            'variation1_name' => [
                'string' => 'option.variation1_name must be a string.',
                'max'    => 'option.variation1_name must not exceed 100 characters.',
            ],
            'variation2_name' => [
                'string' => 'option.variation2_name must be a string.',
                'max'    => 'option.variation2_name must not exceed 100 characters.',
            ],
            'variation1_choice_no' => [
                'string' => 'option.variation1_choice_no must be a string.',
                'max'    => 'option.variation1_choice_no must not exceed 50 characters.',
            ],
            'variation2_choice_no' => [
                'string' => 'option.variation2_choice_no must be a string.',
                'max'    => 'option.variation2_choice_no must not exceed 50 characters.',
            ],
            'variation1_choice_name' => [
                'string' => 'option.variation1_choice_name must be a string.',
                'max'    => 'option.variation1_choice_name must not exceed 100 characters.',
            ],
            'variation2_choice_name' => [
                'string' => 'option.variation2_choice_name must be a string.',
                'max'    => 'option.variation2_choice_name must not exceed 100 characters.',
            ],
            'variation_stock' => [
                'integer' => 'option.variation_stock must be an integer.',
                'min'     => 'option.variation_stock must be at least 0.',
            ],

            'sale_price' => [
                'numeric' => 'option.sale_price must be a number.',
                'min'     => 'option.sale_price must be at least 0.',
            ],
            'dark_market_price' => [
                'numeric' => 'option.dark_market_price must be a number.',
                'min'     => 'option.dark_market_price must be at least 0.',
            ],
            'product_code' => [
                'string' => 'option.product_code must be a string.',
                'max'    => 'option.product_code must not exceed 50 characters.',
            ],
            'option_tax_type' => [
                'in' => 'option.option_tax_type value is invalid.',
            ],
            'jan_code' => [
                'string' => 'option.jan_code must be a string.',
                'max'    => 'option.jan_code must not exceed 13 characters.',
            ],

            'variation1_color_size_flag' => [
                'boolean' => 'option.variation1_color_size_flag must be true or false.',
            ],
            'variation2_color_size_flag' => [
                'boolean' => 'option.variation2_color_size_flag must be true or false.',
            ],
            'handling_status' => [
                'in' => 'option.handling_status value is invalid.',
            ],
        ],

        // upload
        'file' => [
            'required' => 'file is required.',
            'file'     => 'file must be a valid file.',
            'mimes'    => 'file must be a jpg, jpeg, png, gif or webp.',
            'max'      => 'file size must be less than 10 MB.',
        ],
    ],
    'create' => ['success' => 'Product created successfully', 'failed' => 'Failed to create product'],
    'update' => ['success' => 'Product updated successfully', 'failed' => 'Failed to update product'],
    'delete' => ['success' => 'Product deleted successfully', 'failed' => 'Failed to delete product'],
    'list'   => ['message' => 'Product list'],
    'find'   => ['message' => 'Product detail'],
    'upload' => ['success' => 'File uploaded'],
];
