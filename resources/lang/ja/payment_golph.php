<?php

return [
    'update' => [
        'success' => '更新に成功しました。',
        'error' => '更新に失敗しました。',
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
    'card_holder_name' => [
        'required' => 'カード名義人は必須です。',
        'max' => 'カード名義人は255文字以内である必要があります。',
    ],
    'date' => [
        'required' => '日付は必須です。',
        'date_format' => '日付はY-m-d形式である必要があります。',
        'unique' => 'この時間帯は既に予約されています。',
        'unavailable' => '予約できない日付です。',
    ],
    'start_time' => [
        'required' => '開始時間は必須です。',
        'date_format' => '開始時間はH:i形式である必要があります。',
    ],
    'option_type1_id' => [
        'required' => '利用人数オプションは必須です。',
        'exists' => '選択された利用人数オプションは無効です。',
    ],
    'option_type2_id' => [
        'exists' => '選択された時間延長オプションは無効です。',
        'unavailable' => '予約できない日付です。',
    ],
    'instructor_flag' => [
        'required' => 'インストラクターフラグは必須です。',
        'in' => 'インストラクターフラグは0または1である必要があります。',
    ],
    'lesson_flag' => [
        'required' => 'レッスンフラグは必須です。',
        'in' => 'レッスンフラグは0または1である必要があります。',
    ],
    'parking_flag' => [
        'required' => '駐車場フラグは必須です。',
        'in' => '駐車場フラグは0または1である必要があります。',
    ],
    'card_holder_name' => [
        'required_without' => 'カードIDが提供されていない場合、カード名義人は必須です。',
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
        'max' => '番地は255文字以内である必要があります。',
    ],
    'billing_building' => [
        'max' => '建物名は255文字以内である必要があります。',
    ],
    'billing_tel' => [
        'max' => '電話番号は255文字以内である必要があります。',
    ],
    'token_id' => [
        'required_without' => 'カードIDが提供されていない場合、トークンIDは必須です。',
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
];