<?php

return [
    'validation' => [
        'product_id' => [
            'required' => 'product_id は必須です。',
            'string'   => 'product_id は文字列で入力してください。',
            'max'      => 'product_id は50文字以内で入力してください。',
            'exists'   => '指定の product_id は存在しないか削除されています。',
        ],

        'control_flag' => [
            'required'=>'control_flag は必須です。',
            'string'=>'control_flag は文字列で入力してください。',
            'max'=>'control_flag は50文字以内で入力してください。',
        ],
        'publish_status' => [
            'required'=>'publish_status は必須です。',
            'in'=>'publish_status の値が不正です。',
        ],
        'publish_start_date' => [
            'required'=>'publish_start_date は必須です。',
            'date_format'=>'publish_start_date は Y-m-d H:i:s 形式で入力してください。',
        ],
        'publish_end_date' => [
            'date_format'=>'publish_end_date は Y-m-d H:i:s 形式で入力してください。',
        ],
        'product_name' => [
            'required'=>'product_name は必須です。',
            'string'=>'product_name は文字列で入力してください。',
            'max'=>'product_name は255文字以内で入力してください。',
        ],
        'short_description_mobile' => [
            'required'=>'short_description_mobile は必須です。',
            'string'=>'short_description_mobile は文字列で入力してください。',
        ],
        'short_description' => [
            'required'=>'short_description は必須です。',
            'string'=>'short_description は文字列で入力してください。',
        ],

        'stock_type' => [
            'required'=>'stock_type は必須です。',
            'in'=>'stock_type の値が不正です。',
        ],
        'stock_quantity' => [
            'required'=>'stock_quantity は必須です。',
            'integer'=>'stock_quantity は整数で入力してください。',
            'min'=>'stock_quantity は0以上で入力してください。',
        ],
        'sale_price' => [
            'required'=>'sale_price は必須です。',
            'numeric'=>'sale_price は数値で入力してください。',
            'min'=>'sale_price は0以上で入力してください。',
        ],
        'product_code' => [
            'required'=>'product_code は必須です。',
            'string'=>'product_code は文字列で入力してください。',
            'max'=>'product_code は50文字以内で入力してください。',
        ],
        'detail_description_mobile' => [
            'required'=>'detail_description_mobile は必須です。',
            'string'=>'detail_description_mobile は文字列で入力してください。',
        ],
        'detail_description' => [
            'required'=>'detail_description は必須です。',
            'string'=>'detail_description は文字列で入力してください。',
        ],
        'list_price' => [
            'required'=>'list_price は必須です。',
            'numeric'=>'list_price は数値で入力してください。',
            'min'=>'list_price は0以上で入力してください。',
        ],

        'stock_display_setting' => [
            'required'=>'stock_display_setting は必須です。',
            'in'=>'stock_display_setting の値が不正です。',
        ],
        'low_stock_threshold' => [
            'required'=>'low_stock_threshold は必須です。',
            'integer'=>'low_stock_threshold は整数で入力してください。',
            'min'=>'low_stock_threshold は0以上で入力してください。',
        ],
        'soldout_setting' => [
            'required'=>'soldout_setting は必須です。',
            'in'=>'soldout_setting の値が不正です。',
        ],
        'shipping_type' => [
            'required'=>'shipping_type は必須です。',
            'in'=>'shipping_type の値が不正です。',
        ],
        'delivery_type' => [
            'required'=>'delivery_type は必須です。',
            'string'=>'delivery_type は文字列で入力してください。',
            'max'=>'delivery_type は100文字以内で入力してください。',
        ],
        'individual_shipping_fee' => [
            'required'=>'individual_shipping_fee は必須です。',
            'numeric'=>'individual_shipping_fee は数値で入力してください。',
            'min'=>'individual_shipping_fee は0以上で入力してください。',
        ],

        'min_purchase_limit' => [
            'required'=>'min_purchase_limit は必須です。',
            'integer'=>'min_purchase_limit は整数で入力してください。',
            'min'=>'min_purchase_limit は0以上で入力してください。',
        ],
        'max_purchase_limit' => [
            'required'=>'max_purchase_limit は必須です。',
            'integer'=>'max_purchase_limit は整数で入力してください。',
            'min'=>'max_purchase_limit は0以上で入力してください。',
        ],
        'pr_setting' => [
            'required'=>'pr_setting は必須です。',
            'boolean'=>'pr_setting は真偽値で入力してください。',
        ],
        'review_setting' => [
            'required'=>'review_setting は必須です。',
            'boolean'=>'review_setting は真偽値で入力してください。',
        ],
        'bulk_purchase_setting' => [
            'required'=>'bulk_purchase_setting は必須です。',
            'boolean'=>'bulk_purchase_setting は真偽値で入力してください。',
        ],

        'sale_period_enable' => [
            'required'=>'sale_period_enable は必須です。',
            'boolean'=>'sale_period_enable は真偽値で入力してください。',
        ],
        'sale_start_datetime' => [
            'date_format'=>'sale_start_datetime は Y-m-d H:i:s 形式で入力してください。',
        ],
        'sale_end_datetime' => [
            'date_format'=>'sale_end_datetime は Y-m-d H:i:s 形式で入力してください。',
        ],
        'sale_period_display' => [
            'required'=>'sale_period_display は必須です。',
            'boolean'=>'sale_period_display は真偽値で入力してください。',
        ],

        'access_limit' => [
            'required'=>'access_limit は必須です。',
            'in'=>'access_limit の値が不正です。',
        ],
        'display_priority' => [
            'required'=>'display_priority は必須です。',
            'integer'=>'display_priority は整数で入力してください。',
        ],
        'weight' => [
            'required'=>'weight は必須です。',
            'numeric'=>'weight は数値で入力してください。',
            'min'=>'weight は0以上で入力してください。',
        ],

        'thumbnail_url' => [
            'required'=>'thumbnail_url は必須です。',
            'string'=>'thumbnail_url は文字列で入力してください。',
            'max'=>'thumbnail_url は255文字以内で入力してください。',
        ],
        'image_url' => [
            'required'=>'image_url は必須です。',
            'string'=>'image_url は文字列で入力してください。',
            'max'=>'image_url は255文字以内で入力してください。',
        ],
        'image_description' => [
            'required'=>'image_description は必須です。',
            'string'=>'image_description は文字列で入力してください。',
            'max'=>'image_description は255文字以内で入力してください。',
        ],
        'page_title' => [
            'required'=>'page_title は必須です。',
            'string'=>'page_title は文字列で入力してください。',
            'max'=>'page_title は255文字以内で入力してください。',
        ],
        'keywords' => [
            'required'=>'keywords は必須です。',
            'string'=>'keywords は文字列で入力してください。',
            'max'=>'keywords は255文字以内で入力してください。',
        ],
        'description' => [
            'required'=>'description は必須です。',
            'string'=>'description は文字列で入力してください。',
        ],
        'search_keyword_setting' => [
            'required'=>'search_keyword_setting は必須です。',
            'string'=>'search_keyword_setting は文字列で入力してください。',
        ],
        'layout_type' => [
            'required'=>'layout_type は必須です。',
            'string'=>'layout_type は文字列で入力してください。',
            'max'=>'layout_type は100文字以内で入力してください。',
        ],
        'free_area_order' => [
            'string'=>'free_area_order は文字列で入力してください。',
        ],
        'purchase_limit_count' => [
            'integer'=>'purchase_limit_count は整数で入力してください。',
            'min'=>'purchase_limit_count は0以上で入力してください。',
        ],
        'head_insert' => [
            'string'=>'head_insert は文字列で入力してください。',
        ],

        'subscription_setting' => [
            'required'=>'subscription_setting は必須です。',
            'boolean'=>'subscription_setting は真偽値で入力してください。',
        ],
        'mail_bin_limit' => [
            'required'=>'mail_bin_limit は必須です。',
            'integer'=>'mail_bin_limit は整数で入力してください。',
            'min'=>'mail_bin_limit は0以上で入力してください。',
        ],
        'hide_from_list' => [
            'required'=>'hide_from_list は必須です。',
            'boolean'=>'hide_from_list は真偽値で入力してください。',
        ],
        'trial_product_flag' => [
            'required'=>'trial_product_flag は必須です。',
            'boolean'=>'trial_product_flag は真偽値で入力してください。',
        ],
        'payment_disabled' => [
            'string'=>'payment_disabled は文字列で入力してください。',
            'max'=>'payment_disabled は255文字以内で入力してください。',
        ],

        'shipping_disabled' => [
            'required'=>'shipping_disabled は必須です。',
            'boolean'=>'shipping_disabled は真偽値で入力してください。',
        ],
        'release_date' => [
            'required'=>'release_date は必須です。',
            'date_format'=>'release_date は Y-m-d 形式で入力してください。',
        ],
        'release_date_display' => [
            'required'=>'release_date_display は必須です。',
            'boolean'=>'release_date_display は真偽値で入力してください。',
        ],
        'template_selection' => [
            'required'=>'template_selection は必須です。',
            'string'=>'template_selection は文字列で入力してください。',
            'max'=>'template_selection は100文字以内で入力してください。',
        ],
        'variation_price_enable' => [
            'required'=>'variation_price_enable は必須です。',
            'boolean'=>'variation_price_enable は真偽値で入力してください。',
        ],
        'related_products' => [
            'string'=>'related_products は文字列で入力してください。',
        ],

        'tax_category' => [
            'required'=>'tax_category は必須です。',
            'in'=>'tax_category の値が不正です。',
        ],
        'image_title' => [
            'required'=>'image_title は必須です。',
            'string'=>'image_title は文字列で入力してください。',
            'max'=>'image_title は255文字以内で入力してください。',
        ],
        'google_feed_output' => [
            'required'=>'google_feed_output は必須です。',
            'boolean'=>'google_feed_output は真偽値で入力してください。',
        ],
        'facebook_feed_output' => [
            'required'=>'facebook_feed_output は必須です。',
            'boolean'=>'facebook_feed_output は真偽値で入力してください。',
        ],
        'google_product_category' => [
            'required'=>'google_product_category は必須です。',
            'string'=>'google_product_category は文字列で入力してください。',
            'max'=>'google_product_category は255文字以内で入力してください。',
        ],
        'jan_code' => [
            'required'=>'jan_code は必須です。',
            'string'=>'jan_code は文字列で入力してください。',
            'max'=>'jan_code は13文字以内で入力してください。',
        ],
        'color' => [
            'required'=>'color は必須です。',
            'string'=>'color は文字列で入力してください。',
            'max'=>'color は100文字以内で入力してください。',
        ],
        'brand' => [
            'required'=>'brand は必須です。',
            'string'=>'brand は文字列で入力してください。',
            'max'=>'brand は100文字以内で入力してください。',
        ],
        'size' => [
            'required'=>'size は必須です。',
            'string'=>'size は文字列で入力してください。',
            'max'=>'size は50文字以内で入力してください。',
        ],

        'product_option' => [
            'string'=>'product_option は文字列で入力してください。',
        ],
        'common_option' => [
            'string'=>'common_option は文字列で入力してください。',
        ],
        'product_sort_setting' => [
            'string'=>'product_sort_setting は文字列で入力してください。',
        ],
        'social_gift_setting' => [
            'required'=>'social_gift_setting は必須です。',
            'boolean'=>'social_gift_setting は真偽値で入力してください。',
        ],
        'delivery_pattern_setting' => [
            'required'=>'delivery_pattern_setting は必須です。',
            'string'=>'delivery_pattern_setting は文字列で入力してください。',
            'max'=>'delivery_pattern_setting は100文字以内で入力してください。',
        ],

        // options
        'option' => [
            'required' => 'option は必須です。',
            'array'    => 'option は配列で入力してください。',

            'id' => [
                'integer' => 'は整数で入力してください。',
            ],
            'control_flag' => [
                'required' => 'option.control_flag は必須です。',
                'string'   => 'option.control_flag は文字列で入力してください。',
                'max'      => 'option.control_flag は50文字以内で入力してください。',
            ],
            'option_type' => [
                'required' => 'option.option_type は必須です。',
                'in'       => 'option.option_type の値が不正です。',
            ],
            'option_name' => [
                'required' => 'option.option_name は必須です。',
                'string'   => 'option.option_name は文字列で入力してください。',
                'max'      => 'option.option_name は100文字以内で入力してください。',
            ],
            'option_choice_name' => [
                'string' => 'option.option_choice_name は文字列で入力してください。',
                'max'    => 'option.option_choice_name は100文字以内で入力してください。',
            ],
            'option_include_setting' => [
                'string' => 'option.option_include_setting は文字列で入力してください。',
            ],
            'option_exclude_setting' => [
                'string' => 'option.option_exclude_setting は文字列で入力してください。',
            ],
            'option_description' => [
                'string' => 'option.option_description は文字列で入力してください。',
            ],
            'option_rate' => [
                'numeric' => 'option.option_rate は数値で入力してください。',
            ],
            'option_rate_type' => [
                'in' => 'option.option_rate_type の値が不正です。',
            ],

            'variation1_name' => [
                'string' => 'option.variation1_name は文字列で入力してください。',
                'max'    => 'option.variation1_name は100文字以内で入力してください。',
            ],
            'variation2_name' => [
                'string' => 'option.variation2_name は文字列で入力してください。',
                'max'    => 'option.variation2_name は100文字以内で入力してください。',
            ],
            'variation1_choice_no' => [
                'string' => 'option.variation1_choice_no は文字列で入力してください。',
                'max'    => 'option.variation1_choice_no は50文字以内で入力してください。',
            ],
            'variation2_choice_no' => [
                'string' => 'option.variation2_choice_no は文字列で入力してください。',
                'max'    => 'option.variation2_choice_no は50文字以内で入力してください。',
            ],
            'variation1_choice_name' => [
                'string' => 'option.variation1_choice_name は文字列で入力してください。',
                'max'    => 'option.variation1_choice_name は100文字以内で入力してください。',
            ],
            'variation2_choice_name' => [
                'string' => 'option.variation2_choice_name は文字列で入力してください。',
                'max'    => 'option.variation2_choice_name は100文字以内で入力してください。',
            ],
            'variation_stock' => [
                'integer' => 'option.variation_stock は整数で入力してください。',
                'min'     => 'option.variation_stock は0以上で入力してください。',
            ],

            'sale_price' => [
                'numeric' => 'option.sale_price は数値で入力してください。',
                'min'     => 'option.sale_price は0以上で入力してください。',
            ],
            'dark_market_price' => [
                'numeric' => 'option.dark_market_price は数値で入力してください。',
                'min'     => 'option.dark_market_price は0以上で入力してください。',
            ],
            'product_code' => [
                'string' => 'option.product_code は文字列で入力してください。',
                'max'    => 'option.product_code は50文字以内で入力してください。',
            ],
            'option_tax_type' => [
                'in' => 'option.option_tax_type の値が不正です。',
            ],
            'jan_code' => [
                'string' => 'option.jan_code は文字列で入力してください。',
                'max'    => 'option.jan_code は13文字以内で入力してください。',
            ],

            'variation1_color_size_flag' => [
                'boolean' => 'option.variation1_color_size_flag は真偽値で入力してください。',
            ],
            'variation2_color_size_flag' => [
                'boolean' => 'option.variation2_color_size_flag は真偽値で入力してください。',
            ],
            'handling_status' => [
                'in' => 'option.handling_status の値が不正です。',
            ],
        ],

        // upload
        'file' => [
            'required' => 'file は必須です。',
            'file'     => 'file は有効なファイルで入力してください。',
            'mimes'    => 'file は jpg, jpeg, png, gif, webp 形式で入力してください。',
            'max'      => 'file のサイズは 10 MB 以下で入力してください。',
        ],
    ],
    'create' => ['success' => '商品を作成しました', 'failed' => '商品の作成に失敗しました'],
    'update' => ['success' => '商品を更新しました', 'failed' => '商品の更新に失敗しました'],
    'delete' => ['success' => '商品を削除しました', 'failed' => '商品の削除に失敗しました'],
    'list'   => ['message' => '商品一覧'],
    'find'   => ['message' => '商品詳細'],
    'upload' => ['success' => 'ファイルをアップロードしました'],
];