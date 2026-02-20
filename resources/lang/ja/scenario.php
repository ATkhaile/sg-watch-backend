<?php
return [
    'validation' => [
        'page' => [
            'integer' => 'ページは整数である必要があります。',
            'min' => 'ページは1以上である必要があります。',
        ],
        'limit' => [
            'integer' => '制限は整数である必要があります。',
            'min' => '制限は1以上である必要があります。',
        ],
        'per_page' => [
            'integer' => '1ページあたりの項目数は整数である必要があります。',
            'min' => '1ページあたりの項目数は1以上である必要があります。',
            'max' => '1ページあたりの項目数は100以下である必要があります。',
        ],
        'search' => [
            'string' => '検索は文字列である必要があります。',
            'max' => '検索は255文字以下である必要があります。',
        ],
        'sort' => [
            'string' => 'ソートは文字列である必要があります。',
            'in' => '選択されたソートフィールドは無効です。',
        ],
        'direction' => [
            'in' => '方向はASCまたはDESCである必要があります。',
        ],
        'search_name' => [
            'max' => '検索名は256文字以内で入力してください。',
        ],
        'sort_name' => [
            'in' => '名前のソートはASCまたはDESCである必要があります。',
        ],
        'sort_created' => [
            'in' => '作成日のソートはASCまたはDESCである必要があります。',
        ],
        'sort_updated' => [
            'in' => '更新日のソートはASCまたはDESCである必要があります。',
        ],
        'folder_id' => [
            'required' => 'フォルダIDは必須です。',
            'integer' => 'フォルダIDは整数でなければなりません。',
            'exists' => '選択されたフォルダIDは無効です。',
        ],
        'id' => [
            'required' => 'IDは必須です。',
            'integer' => 'IDは整数である必要があります。',
            'exists' => '選択されたIDは無効です。',
        ],
        "folder_name" => [
            'required' => 'フォルダ名は必須です。',
            'string' => 'フォルダ名は文字列である必要があります。',
            'max' => 'フォルダ名は最大255文字である必要があります。',
        ],
        'label' => [
            'required' => 'ラベルは必須です。',
            'string' => 'ラベルは文字列である必要があります。',
            'max' => 'ラベルは255文字以内で入力してください。',
        ],
        'description' => [
            'string' => '説明は文字列である必要があります。',
        ],
        'scenario_id' => [
            'required' => 'シナリオIDは必須です。',
            'integer' => 'シナリオIDは整数でなければなりません。',
            'exists' => '選択されたシナリオIDは無効です。',
        ],
        'delay_type' => [
            'required' => '遅延タイプは必須です。',
            'in' => '選択された遅延タイプは無効です。',
        ],
        'delay_sec' => [
            'integer' => '遅延秒数は整数である必要があります。',
            'min' => '遅延秒数は0以上である必要があります。',
            'required_if' => '遅延タイプが秒数の場合、遅延秒数フィールドは必須です。',
        ],
        'delay_days' => [
            'integer' => '遅延日数は整数である必要があります。',
            'min' => '遅延日数は0以上である必要があります。',
            'required_if' => '遅延タイプが日数の場合、遅延日数フィールドは必須です。',
        ],
        'delay_time' => [
            'date_format' => '遅延時間はH:i形式である必要があります。',
            'required_if' => '遅延タイプが時間の場合、遅延時間フィールドは必須です。',
        ],
        'messages' => [
            'array' => 'messagesは配列で指定してください。',
        ],
        'messages_patterns' => [
            'array' => 'メッセージパターンは配列で指定してください。',
            'min' => 'メッセージパターンは1件以上指定してください。',
            'max' => 'メッセージパターンは:max件以内で指定してください。',
        ],
        'messages_pattern_messages' => [
            'required' => 'メッセージは必須項目です。',
            'array' => 'メッセージは配列で指定してください。',
            'min' => 'メッセージは1件以上指定してください。',
        ],
        'messages_pattern_message_type' => [
            'required' => 'メッセージ種別は必須項目です。',
            'string' => 'メッセージ種別は文字列で指定してください。',
            'in' => '選択されたメッセージ種別が正しくありません。',
        ],
        'messages_pattern_message_content' => [
            'required' => 'メッセージ内容は必須項目です。',
            'string' => 'メッセージ内容は文字列で指定してください。',
            'max' => 'メッセージ内容は:max文字以内で指定してください。',
        ],
        'message_text_required' => 'テキストメッセージを入力してください。',
        'message_image_id_required' => '画像IDを指定してください。',
        'media_not_found' => '指定されたメディアが存在しません。',
        'media_type_must_be_img' => '指定されたメディアは画像ではありません。',

        'media_upload_id_invalid' => '指定されたメディアIDが無効です。',
        'step_ids' => [
            'required' => 'ステップIDフィールドは必須です。',
            'array' => 'ステップIDは配列である必要があります。',
            'min' => 'ステップIDは少なくとも1つの項目が必要です。',
        ],
        'step_ids.*' => [
            'required' => '各ステップIDは必須です。',
            'integer' => '各ステップIDは整数である必要があります。',
            'exists' => '選択されたステップIDの1つ以上が無効です。',
        ],
        'target' => [
            'required' => 'target は必須です。',
            'array' => 'target は配列である必要があります。',

            'conditions' => [
                'type' => [
                    'string' => '条件タイプは文字列で指定してください。',
                    'in' => '条件タイプが不正です。',
                ],
                'rules' => [
                    'array' => 'rules は配列である必要があります。',
                ],
            ],

            'rules' => [
                'type' => [
                    'required' => 'ルールタイプは必須です。',
                    'string' => 'ルールタイプは文字列で指定してください。',
                    'in' => 'ルールタイプが不正です。',
                ],
                'details' => [
                    'required' => 'ルールの詳細は必須です。',
                    'array' => 'ルールの詳細は配列である必要があります。',
                ],
                'condition' => [
                    'required' => '条件は必須です。',
                    'string' => '条件は文字列で指定してください。',
                    'in' => '条件の値が不正です。',
                ],
            ],
        ],

        'actions' => [
            'array' => 'actions は配列である必要があります。',

            'operations' => [
                'required' => 'operations は必須です。',
                'array' => 'operations は配列である必要があります。',
                'min' => 'operations は1件以上指定してください。',
            ],

            'type' => [
                'required' => '操作タイプは必須です。',
                'in' => '操作タイプが不正です。',
            ],

            'details' => [
                'required' => '操作の詳細は必須です。',
                'array' => '操作の詳細は配列である必要があります。',
            ],
        ],
        'tag_id_required' => 'tag_id が必要です。',
        'tag_id_not_allowed' => 'tag_id は使用できません。',
        'tag_id_invalid' => '選択したタグ ID が無効です。',
        'user_id_required' => 'user_id が必要です。',
        'user_id_not_allowed' => 'user_id は使用できません。',
        'user_id_invalid' => '選択したユーザー ID が無効です。',
        'user_id' => [
            'required' => 'ユーザーIDは必須項目です。',
            'integer' => 'ユーザーIDは整数でなければなりません。',
            'exists' => '指定されたユーザーIDは存在しません。',
        ],
        'scenario_step_id' => [
            'required' => 'シナリオステップIDは必須項目です。',
            'integer' => 'シナリオステップIDは整数でなければなりません。',
            'exists' => '指定されたシナリオステップIDは存在しません。',
        ],
        'entry_at' => [
            'date_format' => 'エントリー日時は Y/m/d H:i:s 形式で指定してください。',
        ],
        'schedule_at' => [
            'date_format' => 'スケジュール日時は Y/m/d H:i:s 形式で指定してください。',
        ],
    ],
    'list' => [
        "folder" => [
            'message' => 'シナリオフォルダ一覧',
        ],
        "scenario" => [
            'message' => 'シナリオリスト',
        ],
        'step' => [
            'message' => 'シナリオステップリスト',
        ],
        'user_step' => [
            'message' => 'ユーザーステップリスト',
            'success' => 'ユーザーステップリストを取得しました。',
        ],
        'pinned_images' => [
            'message' => 'ピン留め画像一覧',
        ],
        'tags' => [
            'message' => 'タグ一覧',
        ],
    ],
    'find' => [
        "folder" => [
            'message' => 'シナリオフォルダ詳細',
        ],
        'scenario' => [
            'message' => 'シナリオ詳細',
        ],
        'step' => [
            'message' => 'シナリオステップ詳細',
        ],
        'user_step' => [
            'message' => 'ユーザーステップ詳細',
            'success' => 'ユーザーステップ詳細を取得しました。',
        ],
    ],
    'create' => [
        "folder" => [
            'success' => 'シナリオフォルダの作成に成功しました。',
            'failed' => 'シナリオフォルダの作成に失敗しました。',
        ],
        'scenario' => [
            'success' => 'シナリオの作成に成功しました。',
            'failed' => 'シナリオの作成に失敗しました。',
        ],
        'step' => [
            'success' => 'シナリオステップの作成に成功しました。',
            'failed' => 'シナリオステップの作成に失敗しました。',
        ],
        'user_step' => [
            'success' => 'ユーザーステップの作成に成功しました。',
            'failed'  => 'ユーザーステップの作成に失敗しました。',
        ],
    ],
    'update' => [
        "folder" => [
            'success' => 'シナリオフォルダの更新に成功しました。',
            'failed' => 'シナリオフォルダの更新に失敗しました。',
        ],
        'scenario' => [
            'success' => 'シナリオの更新に成功しました。',
            'failed' => 'シナリオの更新に失敗しました。',
        ],
        'step' => [
            'success' => 'シナリオステップの更新に成功しました。',
            'failed' => 'シナリオステップの更新に失敗しました。',
        ],
        'user_step' => [
            'success' => 'ユーザーステップの更新に成功しました。',
            'failed'  => 'ユーザーステップの更新に失敗しました。',
        ],
    ],
    'delete' => [
        "folder" => [
            'success' => 'シナリオフォルダの削除に成功しました。',
            'failed' => 'シナリオフォルダの削除に失敗しました。',
        ],
        'scenario' => [
            'success' => 'シナリオの削除に成功しました。',
            'failed' => 'シナリオの削除に失敗しました。',
        ],
        'step' => [
            'success' => 'シナリオステップの削除に成功しました。',
            'failed' => 'シナリオステップの削除に失敗しました。',
        ],
        'user_step' => [
            'success' => 'ユーザーステップの削除に成功しました。',
            'failed' => 'ユーザーステップの削除に失敗しました。',
        ],
    ],
    'reorder' => [
        'steps' => [
            'success' => 'シナリオステップの並び替えに成功しました。',
            'failed' => 'シナリオステップの並び替えに失敗しました。',
        ],
    ],
    'action' => [
        'user_step' => [
            'success' => 'ユーザーステップのアクションに成功しました。',
            'failed' => 'ユーザーステップのアクションに失敗しました。',
        ],
    ],
    'crontab' => [
        'success' => 'シナリオの自動実行に成功しました。',
        'failed' => 'シナリオの自動実行に失敗しました。',
    ],
];
