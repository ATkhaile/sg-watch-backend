<?php
return [
    'list' => [
        'message' => '予約一覧',
        'success' => '予約一覧を取得しました。',
    ],
    'detail' => [
        'message' => '予約詳細',
        'success' => '予約詳細を取得しました。',
    ],
    'cancel' => [
        'success' => '管理者により予約をキャンセルしました。',
        'failure' => '予約のキャンセルに失敗しました。',
        'not_found' => '予約が見つからないか、既に処理済みです。'
    ],
    'reservation' => [
        'not_found' => '予約が見つからないか、既に処理済みです。'
    ],
    'id' => [
        'required' => '予約IDは必須です。',
        'integer' => '予約IDは整数である必要があります。',
        'exists' => '指定された予約はこのショップに存在しません。'
    ],
    'validation' => [
        'start_date' => [
            'required' => '開始日は必須です。',
            'date_format' => '開始日は YYYY-MM-DD 形式で指定してください。',
        ],
        'end_date' => [
            'required' => '終了日は必須です。',
            'date_format'    => '終了日は YYYY-MM-DD 形式で指定してください。',
            'after_or_equal' => '終了日は開始日以降である必要があります。',
        ],
        'date' => [
            'required'    => '日付を入力してください。',
            'date_format' => '日付は YYYY-MM-DD 形式で指定してください。',
        ],
        'month' => [
            'required'    => '月を入力してください。',
            'date_format' => '月は YYYY-MM 形式で指定してください。',
        ],
        'reservation_number' => [
            'string' => '予約番号は文字列で入力してください。',
            'max'    => '予約番号は255文字以内で入力してください。',
        ],
        'shop_id' => [
            'required' => '店舗IDは必須です。',
            'integer'  => '店舗IDの形式が正しくありません。',
            'exists'   => '指定された店舗は存在しません。',
        ],
        'id' => [
            'required' => 'IDは必須です。',
            'integer' => 'IDは整数である必要があります。',
            'exists' => '選択されたIDは存在しません。',
        ],
    ],

];
