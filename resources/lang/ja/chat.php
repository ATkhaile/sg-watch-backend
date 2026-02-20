<?php

return [
    'create' => [
        'success' => 'メッセージ作成成功',
        'failed' => 'メッセージ作成失敗'
    ],
    'list' => [
        'message' => 'メッセージ一覧',
    ],
    'not_found' => 'メッセージが見つかりません',
    'invalid' => '無効なチャットモード',
    'online_status_updated' => 'オンラインステータスを更新しました',
    'room' => [
        'joined' => 'チャットルームに参加しました',
        'left' => 'チャットルームを退出しました',
        'join_failed' => 'チャットルームの参加に失敗しました',
        'leave_failed' => 'チャットルームの退出に失敗しました',
    ],
    'users' => [
        'list' => [
            'success' => 'ユーザーリストを取得しました',
        ],
    ],
    'settings' => [
        'updated_successfully' => 'チャット設定を更新しました',
    ],
    'group' => [
        'create' => [
            'success' => 'グループを作成しました',
            'failed' => 'グループの作成に失敗しました'
        ],
        'update' => [
            'success' => 'グループを更新しました',
            'failed' => 'グループの更新に失敗しました'
        ],
        'list' => [
            'success' => 'グループリストを取得しました',
        ],
        'messages' => [
            'success' => 'グループメッセージを取得しました',
        ],
        'history' => [
            'success' => 'グループチャット履歴を取得しました',
        ],
        'message' => [
            'success' => 'グループメッセージを送信しました',
            'failed' => 'グループメッセージの送信に失敗しました'
        ],
        'member' => [
            'added' => 'メンバーをグループに追加しました',
        ],
        'members' => [
            'success' => 'グループメンバーを取得しました',
        ],
        'available_users' => [
            'success' => '利用可能なユーザーを取得しました',
        ],
        'not_found' => 'グループが見つからないか、無効です',
        'not_member' => 'このグループのメンバーではありません',
        'no_permission' => 'この操作を実行する権限がありません',
        'room' => [
            'joined' => 'グループチャットルームに参加しました',
            'left' => 'グループチャットルームを退出しました',
            'join_failed' => 'グループチャットルームの参加に失敗しました',
            'leave_failed' => 'グループチャットルームの退出に失敗しました',
        ],
        'no_permission_send_messages' => 'このグループでメッセージを送信する権限がありません',
    ],
    'validation' => [
        'user_id' => [
            'required' => 'ユーザーIDは必須です。',
            'integer' => 'ユーザーIDは整数である必要があります。',
            'exists' => '指定されたユーザーは存在しません。',
        ],
        'receiver_id' => [
            'required' => '受信者IDは必須です。',
            'integer' => '受信者IDは整数である必要があります。',
            'exists' => '指定された受信者は存在しません。',
        ],
        'message' => [
            'required' => 'メッセージが必要です',
            'string' => 'メッセージは文字列である必要があります',
            'max' => 'メッセージは:max文字を超えることはできません',
        ],
        'file' => [
            'required' => 'ファイルが必要です',
            'invalid' => '無効なファイル形式です',
            'too_large' => 'ファイルサイズが最大許可サイズを超えています',
            'extension_not_allowed' => 'セキュリティ上の理由により、このファイルタイプは許可されていません',
            'mime_type_not_allowed' => 'このファイルタイプはサポートされていません',
        ],
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは1以上である必要があります'
        ],
        'limit' => [
            'integer' => '制限は整数でなければなりません',
            'min' => '制限は1以上である必要があります'
        ],
        'group_id' => [
            'required' => 'グループIDが必要です',
            'integer' => 'グループIDは整数である必要があります',
            'exists' => 'グループが存在しません',
            'not_found' => 'グループが見つかりません',
            'inactive' => 'グループがアクティブではありません',
            'not_member' => 'このグループのメンバーではありません',
        ],
        'user_ids' => [
            'required' => 'ユーザーIDが必要です',
            'array' => 'ユーザーIDは配列である必要があります',
            'min' => '少なくとも1つのユーザーIDが必要です',
            'item_required' => 'ユーザーIDが必要です',
            'item_integer' => 'ユーザーIDは整数である必要があります',
            'duplicate' => '重複するユーザーIDは許可されていません',
            'not_found' => '1つまたは複数のユーザーが存在しません',
        ],
        'status' => [
            'required' => 'ステータスが必要です',
            'invalid' => 'ステータスはonlineまたはofflineである必要があります',
        ],
        'search' => [
            'string' => '検索は文字列である必要があります',
            'max' => '検索は255文字を超えてはいけません',
        ],
        'limit' => [
            'integer' => '制限は整数である必要があります',
            'min' => '制限は少なくとも1である必要があります',
            'max' => '制限は100を超えてはいけません',
        ],
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは少なくとも1である必要があります',
        ],
        'group_ids' => [
            'required' => 'グループIDが必要です',
            'array' => 'グループIDは配列である必要があります',
            'min' => '少なくとも1つのグループIDが必要です',
            'item_required' => 'グループIDが必要です',
            'item_integer' => 'グループIDは整数である必要があります',
            'duplicate' => '重複するグループIDは許可されていません',
            'not_found' => '1つまたは複数のグループが存在しません',
        ],
        'invite_request' => [
            'already_pending' => 'このユーザーへの招待リクエストはすでに保留中です',
            'sent_successfully' => '招待リクエストを送信しました',
            'not_found' => '招待リクエストが見つかりません',
            'already_processed' => 'この招待リクエストはすでに処理されています',
            'approved' => '招待リクエストを承認しました',
            'rejected' => '招待リクエストを拒否しました',
        ],
        'user' => [
            'not_found' => 'ユーザーが見つかりません',
            'already_member' => 'ユーザーは既にグループメンバーです',
            'not_member' => 'ユーザーはこのグループのメンバーではありません',
            'cannot_remove_owner' => 'グループオーナーを削除できません',
            'no_permission_to_remove' => 'このメンバーを削除する権限がありません',
            'cannot_mark_own_messages' => '自分のメッセージを既読にすることはできません',
            'cannot_sort_self' => 'チャット相手リストで自分をソートすることはできません',
            'cannot_change_owner_role' => 'グループオーナーの役割を変更することはできません',
            'owner_cannot_change_own_role' => 'グループオーナーは自分の役割を変更できません',
        ],
        'user_id' => [
            'required' => 'ユーザーIDが必要です',
            'integer' => 'ユーザーIDは整数である必要があります',
            'not_found' => 'ユーザーが見つかりません',
        ],
        'group' => [
            'name' => [
                'required' => 'グループ名が必要です',
                'string' => 'グループ名は文字列である必要があります',
                'max' => 'グループ名は255文字を超えることはできません',
            ],
            'description' => [
                'string' => 'グループの説明は文字列である必要があります',
                'max' => 'グループの説明は1000文字を超えることはできません',
            ],
            'avatar' => [
                'image' => 'グループアバターは画像である必要があります',
                'mimes' => 'グループアバターはjpeg、jpg、png、またはgifである必要があります',
                'max' => 'グループアバターは100MBを超えることはできません',
            ],
            'member_ids' => [
                'array' => 'メンバーIDは配列である必要があります',
                'integer' => 'メンバーIDは整数である必要があります',
                'exists' => 'メンバーが存在しません',
            ],
            'not_member' => 'このグループのメンバーではありません',
            'no_permission' => 'この操作を実行する権限がありません',
            'no_permission_add_members' => 'このグループにメンバーを追加する権限がありません',
            'no_permission_remove_members' => 'このグループからメンバーを削除する権限がありません',
            'only_owner_can_change_roles' => 'グループオーナーのみがメンバーの役割を変更できます',
        ],
        'chat_partner_id' => [
            'required' => 'チャット相手IDが必要です',
            'integer' => 'チャット相手IDは整数である必要があります',
            'exists' => 'チャット相手が存在しません',
        ],
        'custom_name' => [
            'string' => 'カスタム名は文字列である必要があります',
            'max' => 'カスタム名は255文字を超えることはできません',
        ],
        'custom_avatar' => [
            'file' => 'カスタムアバターはファイルである必要があります',
            'image' => 'カスタムアバターは画像である必要があります',
            'mimes' => 'カスタムアバターはjpeg、png、jpg、gif、またはsvgである必要があります',
            'max' => 'カスタムアバターは100MBを超えることはできません',
        ],
        'is_pinned' => [
            'boolean' => 'ピン設定はtrueまたはfalseである必要があります',
        ],
        'is_hidden' => [
            'boolean' => '非表示設定はtrueまたはfalseである必要があります',
            'in' => '非表示設定はtrue、false、1、または0である必要があります',
        ],
        'message_search' => [
            'string' => 'メッセージ検索は文字列である必要があります',
            'max' => 'メッセージ検索は255文字を超えてはいけません',
        ],
        'role' => [
            'required' => '役割が必要です',
            'string' => '役割は文字列である必要があります',
            'invalid' => '役割は管理者またはメンバーである必要があります',
        ],
        'chat_partner_ids' => [
            'required' => 'チャット相手IDが必要です',
            'array' => 'チャット相手IDは配列である必要があります',
            'min' => '少なくとも1つのチャット相手IDが必要です',
            'item_required' => 'チャット相手IDが必要です',
            'item_integer' => 'チャット相手IDは整数である必要があります',
            'duplicate' => '重複するチャット相手IDは許可されていません',
            'not_found' => '1つまたは複数のチャット相手が存在しません',
        ],
    ],
    'attributes' => [
        'group_id' => 'グループ',
        'group_ids' => 'グループ',
        'user_ids' => 'ユーザー',
        'user_id' => 'ユーザー',
        'chat_partner' => 'チャット相手',
        'chat_partners' => 'チャット相手',
        'role' => '役割',
    ],
    'group' => [
        'create' => [
            'success' => 'グループを作成しました',
            'failed' => 'グループの作成に失敗しました'
        ],
        'update' => [
            'success' => 'グループを更新しました',
            'failed' => 'グループの更新に失敗しました'
        ],
        'list' => [
            'success' => 'グループリストを取得しました',
            'failed' => 'グループリストの取得に失敗しました',
        ],
        'messages' => [
            'success' => 'グループメッセージを取得しました',
        ],
        'history' => [
            'success' => 'グループチャット履歴を取得しました',
        ],
        'message' => [
            'success' => 'グループメッセージを送信しました',
            'failed' => 'グループメッセージの送信に失敗しました'
        ],
        'member' => [
            'added' => 'メンバーをグループに追加しました',
        ],
        'members' => [
            'success' => 'グループメンバーを取得しました',
        ],
        'available_users' => [
            'success' => '利用可能なユーザーを取得しました',
        ],
        'not_found' => 'グループが見つからないか、無効です',
        'not_member' => 'このグループのメンバーではありません',
        'no_permission' => 'この操作を実行する権限がありません',
        'no_members_added' => 'グループに追加されたメンバーはいません',
        'members_removed_successfully' => ':count人のメンバーがグループから正常に削除されました',
        'members_partially_removed' => ':total人中:removedのメンバーがグループから削除されました',
        'no_members_removed' => 'グループから削除されたメンバーはいません',
        'member_removed' => 'メンバーがグループから正常に削除されました',
        'remove_member_failed' => 'グループからメンバーを削除できませんでした',
        'permission_denied' => 'この操作を実行する権限がありません',
        'member_role_updated_successfully' => 'メンバーの役割を正常に更新しました',
        'member_role_update_failed' => 'メンバーの役割の更新に失敗しました',
    ],
    'group_pin_toggled_successfully' => 'グループピン状態が正常に更新されました',
    'group_pin_toggle_failed' => 'グループピン状態の更新に失敗しました',
    'group_hidden_toggled_successfully' => 'グループ非表示状態が正常に更新されました',
    'group_hidden_toggle_failed' => 'グループ非表示状態の更新に失敗しました',
    'group_sort_updated_successfully' => 'グループソート順が正常に更新されました',
    'group_sort_update_failed' => 'グループソート順の更新に失敗しました',
    'messages_marked_as_read_successfully' => 'メッセージが正常に既読になりました',
    'group_messages_marked_as_read_successfully' => 'グループメッセージが正常に既読になりました',
    'pin' => [
        'toggled_successfully' => 'チャットピン状態が正常に更新されました',
        'toggle_failed' => 'チャットピン状態の更新に失敗しました',
    ],
    'hidden' => [
        'toggled_successfully' => 'チャット非表示状態が正常に更新されました',
        'toggle_failed' => 'チャット非表示状態の更新に失敗しました',
    ],
    'sort' => [
        'updated_successfully' => 'チャットソート順が正常に更新されました',
        'update_failed' => 'チャットソート順の更新に失敗しました',
    ],
    'typing' => [
        'started' => 'ユーザーが入力を開始しました',
        'stopped' => 'ユーザーが入力を停止しました',
    ],
];
