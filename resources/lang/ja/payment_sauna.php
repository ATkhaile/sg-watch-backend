<?php

return [
    'update' => [
        'success' => '支払い情報が正常に更新されました。',
        'failed' => '支払い情報の更新に失敗しました。',
    ],
    'shop_id' => [
        'required' => '店舗IDは必須です。',
        'integer' => '店舗IDは整数である必要があります。',
        'exists' => '選択された店舗は存在しません。',
    ],
    'card_id' => [
        'required' => 'カードIDは必須です。',
        'integer' => 'カードIDは整数である必要があります。',
        'exists' => '選択されたカードは存在しません。',
    ],
    'date' => [
        'required' => '日付は必須です。',
        'date_format' => '日付はY-m-d形式である必要があります。',
        'after_or_equal' => '日付は今日以降である必要があります。',
        'unique' => 'この日付は既に予約されています。',
    ],
    'option_type1_id' => [
        'exists' => '選択されたオプション1は利用できません。',
    ],
    'option_type2_id' => [
        'exists' => '選択されたオプション2は利用できません。',
    ],
    'parking_flag' => [
        'required' => '駐車場フラグは必須です。',
        'in' => '駐車場フラグは0または1である必要があります。',
    ],
    'usage_option_id' => [
        'required' => '利用オプションは必須です。',
        'integer' => '利用オプションは整数である必要があります。',
    ],
    'card_holder_name' => [
        'required_without' => 'カード名義人はカードIDが提供されていない場合に必須です。',
        'max' => 'カード名義人は255文字以内である必要があります。',
    ],
    'billing_postal_code' => [
        'max' => '郵便番号は8文字以内である必要があります。',
        'regex' => '郵便番号の形式が正しくありません。',
    ],
    'billing_prefecture_id' => [
        'integer' => '都道府県IDは整数である必要があります。',
        'exists' => '選択された都道府県は存在しません。',
    ],
    'billing_city' => [
        'max' => '市区町村は255文字以内である必要があります。',
    ],
    'billing_street_address' => [
        'max' => '住所は255文字以内である必要があります。',
    ],
    'billing_building' => [
        'max' => '建物名は255文字以内である必要があります。',
    ],
    'billing_tel' => [
        'max' => '電話番号は255文字以内である必要があります。',
    ],
    'coupon_id' => [
        'in' => '選択されたクーポンは無効です。',
    ],
    'errors' => [
        'invalid_lesson_time' => '無効なレッスン時間です',
        'invalid_instructor_time' => '無効なインストラクター時間です',
        'invalid_reservation_date' => '無効な予約日です',
        'invalid_booking_time' => '無効な予約時間です',
        'invalid_customer_creation' => '顧客作成に失敗しました',
        'invalid_customer_update' => '顧客更新に失敗しました',
        'invalid_card_addition' => 'カード追加に失敗しました',
        'invalid_card_storage' => 'カード保存に失敗しました',
        'payment_processing_failed' => '決済処理に失敗しました',
    ],
    'success' => [
        'requires_action' => '決済には追加のアクションが必要です。',
        'completed' => '決済が正常に完了しました。',
    ],
];