<?php

/**
 * Permission definitions organized by usecase_group
 *
 * Each permission has:
 * - name: Unique identifier for the permission
 * - display_name: Human-readable name (Japanese) - 短い操作名
 * - description: Description of what the permission allows (Japanese) - 具体的な操作内容と影響範囲
 * - usecase_group: Logical grouping of permissions by use case
 */

return [
    // ============================================================
    // Authorization - ロール・権限管理
    // ============================================================
    'Authorization' => [
        [
            'name' => 'attach-permission-to-role',
            'display_name' => 'ロールへの権限付与',
            'description' => '指定したロールに複数の権限を一括で紐付け、そのロールを持つ全ユーザーに権限を自動的に適用する',
        ],
        [
            'name' => 'attach-permission-to-user',
            'display_name' => 'ユーザーへの権限付与',
            'description' => '特定のユーザーに対してロールを経由せず直接権限を付与する（個別の例外対応用）',
        ],
        [
            'name' => 'attach-role-to-user',
            'display_name' => 'ユーザーへのロール付与',
            'description' => 'ユーザーに複数のロールを割り当て、ロールに紐付く全権限をまとめて付与する',
        ],
        [
            'name' => 'create-permission',
            'display_name' => '権限の新規作成',
            'description' => 'システムで使用する新しい権限（名前・表示名・説明・usecase_group）を定義してDBに登録する',
        ],
        [
            'name' => 'create-role',
            'display_name' => 'ロールの新規作成',
            'description' => '権限のセットをまとめた新しいロール（管理者、編集者など）を定義してDBに登録する',
        ],
        [
            'name' => 'delete-permission',
            'display_name' => '権限の削除',
            'description' => '不要になった権限をDBから完全に削除する（関連するロール・ユーザーからも自動的に削除）',
        ],
        [
            'name' => 'delete-role',
            'display_name' => 'ロールの削除',
            'description' => '不要になったロールをDBから完全に削除する（そのロールを持つユーザーからも自動的に削除）',
        ],
        [
            'name' => 'find-permission',
            'display_name' => '権限詳細の取得',
            'description' => '指定した権限のID・名前・表示名・説明・usecase_groupなどの詳細情報を取得する',
        ],
        [
            'name' => 'find-role',
            'display_name' => 'ロール詳細の取得',
            'description' => '指定したロールのID・名前・説明・紐付いている権限数などの詳細情報を取得する',
        ],
        [
            'name' => 'list-permission',
            'display_name' => '権限一覧の取得',
            'description' => 'システムに登録されている全権限をページネーション付きで一覧表示する',
        ],
        [
            'name' => 'list-role',
            'display_name' => 'ロール一覧の取得',
            'description' => 'システムに登録されている全ロールをページネーション付きで一覧表示する',
        ],
        [
            'name' => 'list-permission-from-role',
            'display_name' => 'ロールの権限確認',
            'description' => '指定したロールに紐付いている全ての権限を一覧で取得する',
        ],
        [
            'name' => 'list-permission-from-user',
            'display_name' => 'ユーザーの権限確認',
            'description' => '指定したユーザーが持つ全権限を取得する（ロール経由と直接付与の両方を含む）',
        ],
        [
            'name' => 'list-role-from-user',
            'display_name' => 'ユーザーのロール確認',
            'description' => '指定したユーザーに割り当てられている全ロールを一覧で取得する',
        ],
        [
            'name' => 'revoke-permission-to-role',
            'display_name' => 'ロールからの権限削除',
            'description' => '指定したロールから特定の権限の紐付けを解除し、そのロールを持つユーザーからも権限を除外する',
        ],
        [
            'name' => 'revoke-permission-to-user',
            'display_name' => 'ユーザーからの権限削除',
            'description' => '指定したユーザーから直接付与された権限を削除する（ロール経由の権限には影響しない）',
        ],
        [
            'name' => 'revoke-role-to-user',
            'display_name' => 'ユーザーからのロール削除',
            'description' => '指定したユーザーから特定のロールを削除し、そのロールに紐付く権限を除外する',
        ],
        [
            'name' => 'update-permission',
            'display_name' => '権限情報の編集',
            'description' => '既存の権限の表示名・説明・usecase_groupなどのメタ情報を変更する',
        ],
        [
            'name' => 'update-role',
            'display_name' => 'ロール情報の編集',
            'description' => '既存のロールの名前・説明などのメタ情報を変更する',
        ],
    ],

    // ============================================================
    // Users - ユーザー管理
    // ============================================================
    'Users' => [
        [
            'name' => 'create-users',
            'display_name' => 'ユーザーの新規作成',
            'description' => '名前・メールアドレス・パスワード・プロフィール情報を入力して新規ユーザーアカウントを作成する',
        ],
        [
            'name' => 'delete-users',
            'display_name' => 'ユーザーの削除',
            'description' => '指定したユーザーアカウントをDBから完全に削除する（関連データも含む）',
        ],
        [
            'name' => 'find-users',
            'display_name' => 'ユーザー詳細の取得',
            'description' => '指定したユーザーの名前・メール・アバターURL・メンバーシップ・権限などの詳細情報を取得する',
        ],
        [
            'name' => 'list-users',
            'display_name' => 'ユーザー一覧の取得',
            'description' => '全ユーザーをページネーション付きで一覧表示する（名前・メール・登録日でフィルタ/検索可能）',
        ],
        [
            'name' => 'update-users',
            'display_name' => 'ユーザー情報の編集',
            'description' => '指定したユーザーの名前・メール・プロフィール・アバター・カバー画像などを変更する',
        ],
    ],

    // ============================================================
    // Category - カテゴリ管理
    // ============================================================
    'Category' => [
        [
            'name' => 'create-category',
            'display_name' => 'カテゴリの新規作成',
            'description' => 'ニュースやコンテンツを分類するための新しいカテゴリ（名前・スラッグ・親カテゴリ）を作成する',
        ],
        [
            'name' => 'delete-category',
            'display_name' => 'カテゴリの削除',
            'description' => '指定したカテゴリをDBから削除する（そのカテゴリに属するコンテンツは未分類になる）',
        ],
        [
            'name' => 'find-category',
            'display_name' => 'カテゴリ詳細の取得',
            'description' => '指定したカテゴリのID・名前・スラッグ・親カテゴリ・所属コンテンツ数などを取得する',
        ],
        [
            'name' => 'list-category',
            'display_name' => 'カテゴリ一覧の取得',
            'description' => '全カテゴリを階層構造で一覧表示する（親子関係を含む）',
        ],
        [
            'name' => 'update-category',
            'display_name' => 'カテゴリ情報の編集',
            'description' => '既存のカテゴリの名前・スラッグ・親カテゴリなどを変更する',
        ],
    ],

    // ============================================================
    // Tags - タグ管理
    // ============================================================
    'Tags' => [
        [
            'name' => 'create-tags',
            'display_name' => 'タグの新規作成',
            'description' => 'コンテンツに付与するための新しいタグ（名前・スラッグ）を作成する',
        ],
        [
            'name' => 'delete-tags',
            'display_name' => 'タグの削除',
            'description' => '指定したタグをDBから削除する（コンテンツからもタグの紐付けが解除される）',
        ],
        [
            'name' => 'find-tags',
            'display_name' => 'タグ詳細の取得',
            'description' => '指定したタグのID・名前・スラッグ・使用回数などの情報を取得する',
        ],
        [
            'name' => 'list-tags',
            'display_name' => 'タグ一覧の取得',
            'description' => '全タグを一覧表示する（使用回数順や名前順でソート可能）',
        ],
        [
            'name' => 'update-tags',
            'display_name' => 'タグ情報の編集',
            'description' => '既存のタグの名前・スラッグを変更する',
        ],
    ],

    // ============================================================
    // News - ニュース管理
    // ============================================================
    'News' => [
        [
            'name' => 'create-news',
            'display_name' => 'ニュースの新規作成',
            'description' => 'タイトル・本文・サムネイル・カテゴリ・タグ・公開日時を設定してニュース記事を作成する',
        ],
        [
            'name' => 'delete-news',
            'display_name' => 'ニュースの削除',
            'description' => '指定したニュース記事をDBから完全に削除する',
        ],
        [
            'name' => 'find-news',
            'display_name' => 'ニュース詳細の取得',
            'description' => '指定したニュース記事の全情報（タイトル・本文・画像・カテゴリ・タグ・公開状態）を取得する',
        ],
        [
            'name' => 'list-news',
            'display_name' => 'ニュース一覧の取得',
            'description' => '全ニュースをページネーション付きで一覧表示する（カテゴリ・タグ・ステータス・重要度でフィルタ可能）',
        ],
        [
            'name' => 'update-news',
            'display_name' => 'ニュース情報の編集',
            'description' => '既存のニュース記事のタイトル・本文・画像・カテゴリ・タグ・公開状態を変更する',
        ],
        [
            'name' => 'import-news',
            'display_name' => 'ニュースの一括インポート',
            'description' => 'CSVファイルから複数のニュース記事を一括でインポート・登録する',
        ],
    ],

    // ============================================================
    // Sessions - セッション管理
    // ============================================================
    'Sessions' => [
        [
            'name' => 'terminate-sessions',
            'display_name' => 'セッションの強制終了',
            'description' => '指定したセッションを無効化してユーザーを強制ログアウトさせる（不正アクセス対応用）',
        ],
        [
            'name' => 'find-sessions',
            'display_name' => 'セッション詳細の取得',
            'description' => '指定したセッションのIPアドレス・User-Agent・ログイン時刻・最終アクティビティを取得する',
        ],
        [
            'name' => 'list-sessions',
            'display_name' => 'セッション一覧の取得',
            'description' => '全アクティブセッションをページネーション付きで一覧表示する（ユーザー・IP・デバイス情報含む）',
        ],
        [
            'name' => 'delete-session-device',
            'display_name' => 'セッションデバイスの削除',
            'description' => 'ユーザーの特定のセッションデバイスを削除（強制終了）する',
        ],
    ],

    // ============================================================
    // ChatGuest - ゲストチャット管理
    // ============================================================
    'ChatGuest' => [
        [
            'name' => 'reply-chatguest',
            'display_name' => 'ゲストへの返信',
            'description' => '未ログインゲストからの問い合わせチャットに対して管理者として返信を送信する',
        ],
        [
            'name' => 'history-chatguest',
            'display_name' => 'ゲストチャット履歴の取得',
            'description' => '指定したゲスト（メールアドレス識別）との過去のチャット履歴を時系列で取得する',
        ],
        [
            'name' => 'list-chatguest',
            'display_name' => 'ゲストチャット一覧の取得',
            'description' => '全ゲストからの問い合わせチャットを一覧表示する（未対応・対応済みでフィルタ可能）',
        ],
    ],

    // ============================================================
    // Chat - チャット管理
    // ============================================================
    'Chat' => [
        [
            'name' => 'list-chat-users',
            'display_name' => 'チャット可能ユーザー一覧',
            'description' => '1対1チャットで会話可能なユーザーの一覧を取得する（オンライン状態含む）',
        ],
    ],

    // ============================================================
    // Plans - プラン管理
    // ============================================================
    'Plans' => [
        [
            'name' => 'create-plans',
            'display_name' => 'プランの新規作成',
            'description' => '料金・期間・特典・説明を設定して新しいサービスプランを作成する',
        ],
        [
            'name' => 'delete-plans',
            'display_name' => 'プランの削除',
            'description' => '指定したプランをDBから削除する（既存の購入者には影響しない設計推奨）',
        ],
        [
            'name' => 'find-plans',
            'display_name' => 'プラン詳細の取得',
            'description' => '指定したプランの料金・期間・特典・紐付くメンバーシップなどの詳細情報を取得する',
        ],
        [
            'name' => 'list-plans',
            'display_name' => 'プラン一覧の取得',
            'description' => '全プランをページネーション付きで一覧表示する（有効/無効・料金でフィルタ可能）',
        ],
        [
            'name' => 'update-plans',
            'display_name' => 'プラン情報の編集',
            'description' => '既存のプランの料金・期間・特典・説明・公開状態を変更する',
        ],
    ],

    // ============================================================
    // MySns - MySns管理
    // ============================================================
    'MySns' => [
        [
            'name' => 'list-my-sns',
            'display_name' => 'MySns一覧の取得',
            'description' => '全ユーザーのSNS連携情報（Twitter・Instagram・Facebookなど）を一覧表示する',
        ],
    ],

    // ============================================================
    // MySnsBot - MySnsBot管理
    // ============================================================
    'MySnsBot' => [
        [
            'name' => 'create-my-sns-bot',
            'display_name' => 'SNSボットの新規作成',
            'description' => 'SNS自動投稿用のボット設定（投稿内容・スケジュール・対象SNS）を作成する',
        ],
        [
            'name' => 'delete-my-sns-bot',
            'display_name' => 'SNSボットの削除',
            'description' => '指定したSNSボット設定をDBから削除し、自動投稿を停止する',
        ],
        [
            'name' => 'find-my-sns-bot',
            'display_name' => 'SNSボット詳細の取得',
            'description' => '指定したSNSボットの設定内容・投稿履歴・次回投稿予定などを取得する',
        ],
        [
            'name' => 'list-my-sns-bot',
            'display_name' => 'SNSボット一覧の取得',
            'description' => '全SNSボット設定を一覧表示する（稼働中・停止中でフィルタ可能）',
        ],
        [
            'name' => 'update-my-sns-bot',
            'display_name' => 'SNSボット設定の編集',
            'description' => '既存のSNSボットの投稿内容・スケジュール・有効/無効を変更する',
        ],
    ],

    // ============================================================
    // Dify - Dify連携管理
    // ============================================================
    'Dify' => [
        [
            'name' => 'create-dify',
            'display_name' => 'Dify連携の新規作成',
            'description' => 'Dify AIプラットフォームとの連携設定（APIキー・エンドポイント）を作成する',
        ],
        [
            'name' => 'delete-dify',
            'display_name' => 'Dify連携の削除',
            'description' => '指定したDify連携設定をDBから削除し、AI機能を無効化する',
        ],
        [
            'name' => 'find-dify',
            'display_name' => 'Dify連携詳細の取得',
            'description' => '指定したDify連携のAPIキー・エンドポイント・使用状況などを取得する',
        ],
        [
            'name' => 'list-dify',
            'display_name' => 'Dify連携一覧の取得',
            'description' => '全Dify連携設定を一覧表示する',
        ],
        [
            'name' => 'update-dify',
            'display_name' => 'Dify連携設定の編集',
            'description' => '既存のDify連携のAPIキー・エンドポイント・有効/無効を変更する',
        ],
    ],

    // ============================================================
    // MediaUploads - メディアアップロード管理
    // ============================================================
    'MediaUploads' => [
        [
            'name' => 'create-media-uploads',
            'display_name' => 'メディアのアップロード',
            'description' => '画像・動画・ドキュメントなどのファイルをサーバーにアップロードしてDBに登録する',
        ],
        [
            'name' => 'delete-media-uploads',
            'display_name' => 'メディアの削除',
            'description' => '指定したメディアファイルをサーバーとDBから完全に削除する',
        ],
        [
            'name' => 'find-media-uploads',
            'display_name' => 'メディア詳細の取得',
            'description' => '指定したメディアファイルのURL・サイズ・MIME Type・アップロード日時などを取得する',
        ],
        [
            'name' => 'list-media-uploads',
            'display_name' => 'メディア一覧の取得',
            'description' => '全メディアファイルをページネーション付きで一覧表示する（ファイルタイプ・日付でフィルタ可能）',
        ],
        [
            'name' => 'update-media-uploads',
            'display_name' => 'メディア情報の編集',
            'description' => '指定したメディアファイルの名前・ALTテキスト・所属フォルダなどのメタ情報を変更する',
        ],
    ],

    // ============================================================
    // MediaFolders - メディアフォルダ管理
    // ============================================================
    'MediaFolders' => [
        [
            'name' => 'create-media-folders',
            'display_name' => 'フォルダの新規作成',
            'description' => 'メディアファイルを整理するための新しいフォルダ（名前・親フォルダ）を作成する',
        ],
        [
            'name' => 'delete-media-folders',
            'display_name' => 'フォルダの削除',
            'description' => '指定したフォルダをDBから削除する（中のファイルは未分類フォルダに移動）',
        ],
        [
            'name' => 'find-media-folders',
            'display_name' => 'フォルダ詳細の取得',
            'description' => '指定したフォルダの名前・親フォルダ・含まれるファイル数などを取得する',
        ],
        [
            'name' => 'list-media-folders',
            'display_name' => 'フォルダ一覧の取得',
            'description' => '全メディアフォルダを階層構造で一覧表示する',
        ],
        [
            'name' => 'update-media-folders',
            'display_name' => 'フォルダ情報の編集',
            'description' => '既存のフォルダの名前・親フォルダを変更する',
        ],
    ],

    // ============================================================
    // Notifications - 通知管理
    // ============================================================
    'Notifications' => [
        [
            'name' => 'create-notifications',
            'display_name' => '通知の新規作成',
            'description' => 'システム通知・お知らせ（タイトル・本文・対象ユーザー・表示期間）を作成する',
        ],
        [
            'name' => 'delete-notifications',
            'display_name' => '通知の削除',
            'description' => '指定した通知をDBから完全に削除する',
        ],
        [
            'name' => 'find-notifications',
            'display_name' => '通知詳細の取得',
            'description' => '指定した通知のタイトル・本文・対象ユーザー・既読状況などを取得する',
        ],
        [
            'name' => 'list-notifications',
            'display_name' => '通知一覧の取得',
            'description' => '全通知をページネーション付きで一覧表示する（日付・タイプでフィルタ可能）',
        ],
        [
            'name' => 'update-notifications',
            'display_name' => '通知情報の編集',
            'description' => '既存の通知のタイトル・本文・表示期間などを変更する',
        ],
    ],

    // ============================================================
    // Firebase - Firebase通知管理
    // ============================================================
    'Firebase' => [
        [
            'name' => 'list-firebase-notifications',
            'display_name' => 'Firebase通知一覧の取得',
            'description' => 'ユーザーに送信されたFirebaseプッシュ通知の履歴を一覧表示する',
        ],
        [
            'name' => 'list-firebase-unread-notifications',
            'display_name' => 'Firebase未読通知の取得',
            'description' => 'ユーザーがまだ確認していない未読のFirebase通知のみを取得する',
        ],
        [
            'name' => 'update-firebase-notification-readed',
            'display_name' => 'Firebase通知の既読化',
            'description' => '指定したFirebase通知を既読状態に更新する',
        ],
    ],

    // ============================================================
    // Affiliates - アフィリエイト管理
    // ============================================================
    'Affiliates' => [
        [
            'name' => 'list-affiliates',
            'display_name' => 'アフィリエイト一覧の取得',
            'description' => '全アフィリエイトパートナーを一覧表示する（紹介コード・成果数・報酬額含む）',
        ],
        [
            'name' => 'create-affiliates',
            'display_name' => 'アフィリエイトの新規作成',
            'description' => '新しいアフィリエイトパートナー（紹介コード・報酬率・有効期間）を登録する',
        ],
        [
            'name' => 'delete-affiliates',
            'display_name' => 'アフィリエイトの削除',
            'description' => '指定したアフィリエイト設定をDBから削除し、紹介コードを無効化する',
        ],
    ],

    // ============================================================
    // Schedules - スケジュール管理
    // ============================================================
    'Schedules' => [
        [
            'name' => 'list-schedules',
            'display_name' => 'スケジュール一覧の取得',
            'description' => '全スケジュール（イベント・予約枠）をカレンダー形式で一覧表示する',
        ],
        [
            'name' => 'find-schedules',
            'display_name' => 'スケジュール詳細の取得',
            'description' => '指定したスケジュールの日時・場所・サービス・予約状況などを取得する',
        ],
        [
            'name' => 'create-schedules',
            'display_name' => 'スケジュールの新規作成',
            'description' => '新しいスケジュール（日時・場所・サービス・定員）を作成する',
        ],
        [
            'name' => 'update-schedules',
            'display_name' => 'スケジュール情報の編集',
            'description' => '既存のスケジュールの日時・場所・サービス・定員などを変更する',
        ],
        [
            'name' => 'delete-schedules',
            'display_name' => 'スケジュールの削除',
            'description' => '指定したスケジュールをDBから削除する（既存の予約者には通知推奨）',
        ],
    ],

    // ============================================================
    // ScheduleTags - スケジュールタグ管理
    // ============================================================
    'ScheduleTags' => [
        [
            'name' => 'list-schedule-tags',
            'display_name' => 'スケジュールタグ一覧の取得',
            'description' => 'スケジュールを分類するためのタグを一覧表示する',
        ],
        [
            'name' => 'find-schedule-tags',
            'display_name' => 'スケジュールタグ詳細の取得',
            'description' => '指定したスケジュールタグの名前・色・使用回数などを取得する',
        ],
        [
            'name' => 'create-schedule-tags',
            'display_name' => 'スケジュールタグの新規作成',
            'description' => '新しいスケジュールタグ（名前・色・アイコン）を作成する',
        ],
        [
            'name' => 'update-schedule-tags',
            'display_name' => 'スケジュールタグ情報の編集',
            'description' => '既存のスケジュールタグの名前・色・アイコンを変更する',
        ],
        [
            'name' => 'delete-schedule-tags',
            'display_name' => 'スケジュールタグの削除',
            'description' => '指定したスケジュールタグをDBから削除する',
        ],
    ],

    // ============================================================
    // Places - 場所管理
    // ============================================================
    'Places' => [
        [
            'name' => 'list-places',
            'display_name' => '場所一覧の取得',
            'description' => '全登録場所（会場・店舗）を一覧表示する（住所・収容人数含む）',
        ],
        [
            'name' => 'find-places',
            'display_name' => '場所詳細の取得',
            'description' => '指定した場所の名前・住所・収容人数・設備情報などを取得する',
        ],
        [
            'name' => 'create-places',
            'display_name' => '場所の新規作成',
            'description' => '新しい場所（名前・住所・収容人数・設備）を登録する',
        ],
        [
            'name' => 'update-places',
            'display_name' => '場所情報の編集',
            'description' => '既存の場所の名前・住所・収容人数・設備情報を変更する',
        ],
        [
            'name' => 'delete-places',
            'display_name' => '場所の削除',
            'description' => '指定した場所をDBから削除する',
        ],
    ],

    // ============================================================
    // Services - サービス管理
    // ============================================================
    'Services' => [
        [
            'name' => 'list-services',
            'display_name' => 'サービス一覧の取得',
            'description' => '予約可能なサービス（コース・メニュー）を一覧表示する（料金・所要時間含む）',
        ],
        [
            'name' => 'find-services',
            'display_name' => 'サービス詳細の取得',
            'description' => '指定したサービスの名前・料金・所要時間・説明などを取得する',
        ],
        [
            'name' => 'create-services',
            'display_name' => 'サービスの新規作成',
            'description' => '新しいサービス（名前・料金・所要時間・説明）を登録する',
        ],
        [
            'name' => 'update-services',
            'display_name' => 'サービス情報の編集',
            'description' => '既存のサービスの名前・料金・所要時間・説明を変更する',
        ],
        [
            'name' => 'delete-services',
            'display_name' => 'サービスの削除',
            'description' => '指定したサービスをDBから削除する',
        ],
    ],

    // ============================================================
    // PusherNotification - Pusher通知管理
    // ============================================================
    'PusherNotification' => [
        [
            'name' => 'list-pusher-notifications',
            'display_name' => 'Pusher通知一覧の取得',
            'description' => 'ユーザーに送信されたPusherリアルタイム通知の履歴を一覧表示する',
        ],
        [
            'name' => 'list-pusher-unread-notifications',
            'display_name' => 'Pusher未読通知の取得',
            'description' => 'ユーザーがまだ確認していない未読のPusher通知のみを取得する',
        ],
        [
            'name' => 'update-pusher-notification-readed',
            'display_name' => 'Pusher通知の既読化',
            'description' => '指定したPusher通知を既読状態に更新する',
        ],
    ],

    // ============================================================
    // SystemSetting - システム設定管理
    // ============================================================
    'SystemSetting' => [
        [
            'name' => 'detail-system-setting',
            'display_name' => 'システム設定の取得',
            'description' => '指定したシステム設定項目（サイト名・ロゴ・メール設定など）の現在値を取得する',
        ],
        [
            'name' => 'update-system-setting',
            'display_name' => 'システム設定の編集',
            'description' => 'システム設定項目の値を変更する（サイト全体に即時反映）',
        ],
    ],

    // ============================================================
    // RankMaster - ランクマスター管理
    // ============================================================
    'RankMaster' => [
        [
            'name' => 'list-rank-masters',
            'display_name' => 'ランク一覧の取得',
            'description' => '全ユーザーランク（ブロンズ・シルバー・ゴールドなど）の定義を一覧表示する',
        ],
        [
            'name' => 'find-rank-master',
            'display_name' => 'ランク詳細の取得',
            'description' => '指定したランクの名前・必要経験値・特典などの情報を取得する',
        ],
        [
            'name' => 'create-rank-master',
            'display_name' => 'ランクの新規作成',
            'description' => '新しいユーザーランク（名前・必要経験値・アイコン・特典）を定義する',
        ],
        [
            'name' => 'update-rank-master',
            'display_name' => 'ランク情報の編集',
            'description' => '既存のランクの名前・必要経験値・特典などを変更する',
        ],
        [
            'name' => 'delete-rank-master',
            'display_name' => 'ランクの削除',
            'description' => '指定したランク定義をDBから削除する',
        ],
        [
            'name' => 'find-rank-user',
            'display_name' => 'ユーザーランクの取得',
            'description' => '指定したユーザーの現在のランク・経験値・次ランクまでの経験値を取得する',
        ],
    ],

    // ============================================================
    // ExperienceHistory - 経験値履歴管理
    // ============================================================
    'ExperienceHistory' => [
        [
            'name' => 'list-experience-histories',
            'display_name' => '経験値履歴一覧の取得',
            'description' => '指定ユーザーの経験値獲得履歴を時系列で一覧表示する（獲得理由・日時含む）',
        ],
        [
            'name' => 'find-experience-history',
            'display_name' => '経験値履歴詳細の取得',
            'description' => '指定した経験値履歴の獲得ポイント・理由・日時などを取得する',
        ],
        [
            'name' => 'create-experience-history',
            'display_name' => '経験値の手動付与',
            'description' => '指定ユーザーに経験値を手動で付与する（管理者調整用）',
        ],
        [
            'name' => 'update-experience-history',
            'display_name' => '経験値履歴の編集',
            'description' => '既存の経験値履歴のポイント・理由を修正する（誤登録の訂正用）',
        ],
        [
            'name' => 'delete-experience-history',
            'display_name' => '経験値履歴の削除',
            'description' => '指定した経験値履歴をDBから削除する（ユーザーの経験値も減算される）',
        ],
    ],

    // ============================================================
    // PointHistory - ポイント履歴管理
    // ============================================================
    'PointHistory' => [
        [
            'name' => 'list-point-histories',
            'display_name' => 'ポイント履歴一覧の取得',
            'description' => '指定ユーザーのポイント獲得・消費履歴を時系列で一覧表示する',
        ],
        [
            'name' => 'find-point-history',
            'display_name' => 'ポイント履歴詳細の取得',
            'description' => '指定したポイント履歴のポイント数・理由・日時などを取得する',
        ],
        [
            'name' => 'create-point-history',
            'display_name' => 'ポイントの手動付与',
            'description' => '指定ユーザーにポイントを手動で付与する（キャンペーン・補償用）',
        ],
        [
            'name' => 'update-point-history',
            'display_name' => 'ポイント履歴の編集',
            'description' => '既存のポイント履歴のポイント数・理由を修正する（誤登録の訂正用）',
        ],
        [
            'name' => 'delete-point-history',
            'display_name' => 'ポイント履歴の削除',
            'description' => '指定したポイント履歴をDBから削除する（ユーザーのポイントも調整される）',
        ],
    ],

    // ============================================================
    // DailyBonus - デイリーボーナス管理
    // ============================================================
    'DailyBonus' => [
        [
            'name' => 'get-daily-bonus',
            'display_name' => 'デイリーボーナス設定の取得',
            'description' => '現在のデイリーボーナス設定（ポイント数・連続ログインボーナス）を取得する',
        ],
        [
            'name' => 'create-daily-bonus',
            'display_name' => 'デイリーボーナスの設定',
            'description' => 'デイリーボーナスのポイント数・連続ログインボーナス設定を作成・更新する',
        ],
    ],

    // ============================================================
    // UserPointDaily - ユーザーポイントデイリー管理
    // ============================================================
    'UserPointDaily' => [
        [
            'name' => 'list-user-point-daily',
            'display_name' => 'デイリーポイント獲得一覧',
            'description' => 'ユーザーのデイリーポイント獲得履歴を一覧表示する（連続ログイン日数含む）',
        ],
        [
            'name' => 'create-user-point-daily',
            'display_name' => 'デイリーポイントの付与',
            'description' => '指定ユーザーにデイリーポイントを手動で付与する',
        ],
        [
            'name' => 'detail-user-point-daily',
            'display_name' => 'デイリーポイント詳細の取得',
            'description' => '指定したデイリーポイント履歴のポイント数・獲得日・連続日数を取得する',
        ],
        [
            'name' => 'update-user-point-daily',
            'display_name' => 'デイリーポイント情報の編集',
            'description' => '既存のデイリーポイント履歴を修正する',
        ],
        [
            'name' => 'delete-user-point-daily',
            'display_name' => 'デイリーポイント履歴の削除',
            'description' => '指定したデイリーポイント履歴をDBから削除する',
        ],
    ],

    // ============================================================
    // UserPointAffiliate - ユーザーポイントアフィリエイト管理
    // ============================================================
    'UserPointAffiliate' => [
        [
            'name' => 'list-user-point-affiliates',
            'display_name' => 'アフィリエイトポイント一覧',
            'description' => 'ユーザーのアフィリエイト紹介で獲得したポイント履歴を一覧表示する',
        ],
        [
            'name' => 'create-user-point-affiliate',
            'display_name' => 'アフィリエイトポイントの付与',
            'description' => '指定ユーザーにアフィリエイト報酬ポイントを手動で付与する',
        ],
        [
            'name' => 'detail-user-point-affiliate',
            'display_name' => 'アフィリエイトポイント詳細',
            'description' => '指定したアフィリエイトポイント履歴の被紹介者・ポイント数・日時を取得する',
        ],
        [
            'name' => 'update-user-point-affiliate',
            'display_name' => 'アフィリエイトポイントの編集',
            'description' => '既存のアフィリエイトポイント履歴を修正する',
        ],
        [
            'name' => 'delete-user-point-affiliate',
            'display_name' => 'アフィリエイトポイントの削除',
            'description' => '指定したアフィリエイトポイント履歴をDBから削除する',
        ],
    ],

    // ============================================================
    // AffiliateBonus - アフィリエイトボーナス管理
    // ============================================================
    'AffiliateBonus' => [
        [
            'name' => 'get-affiliate-bonus',
            'display_name' => 'アフィリエイトボーナス設定の取得',
            'description' => '現在のアフィリエイトボーナス設定（報酬率・ポイント数）を取得する',
        ],
        [
            'name' => 'create-affiliate-bonus',
            'display_name' => 'アフィリエイトボーナスの設定',
            'description' => 'アフィリエイトボーナスの報酬率・ポイント数設定を作成・更新する',
        ],
    ],

    // ============================================================
    // WorkManagement - ワーク管理
    // ============================================================
    'WorkManagement' => [
        [
            'name' => 'get-work-management',
            'display_name' => 'ワーク記録一覧の取得',
            'description' => '指定ユーザーのワーク記録を一覧表示する',
        ],
        [
            'name' => 'create-work-management',
            'display_name' => 'ワーク記録の新規作成',
            'description' => '指定ユーザーのワーク記録を手動で登録する',
        ],
    ],

    // ============================================================
    // CustomLink - カスタムリンク管理
    // ============================================================
    'CustomLink' => [
        [
            'name' => 'create-custom-links',
            'display_name' => 'カスタムリンクの作成',
            'description' => 'トラッキング用のカスタムリンク（URL・プレフィックス・有効期限）を作成する',
        ],
        [
            'name' => 'view-custom-link-analytics',
            'display_name' => 'カスタムリンク分析の閲覧',
            'description' => 'カスタムリンクのアクセス数・クリック推移・参照元などの分析データを閲覧する',
        ],
    ],

    // ============================================================
    // AppSetting - アプリ設定管理
    // ============================================================
    'AppSetting' => [
        [
            'name' => 'list-app-settings',
            'display_name' => 'アプリ設定一覧の取得',
            'description' => '全アプリ設定項目（テーマ・言語・通知設定など）を一覧表示する',
        ],
        [
            'name' => 'create-app-setting',
            'display_name' => 'アプリ設定の新規作成',
            'description' => '新しいアプリ設定項目（キー・値・タイプ）を作成する',
        ],
        [
            'name' => 'detail-app-setting',
            'display_name' => 'アプリ設定詳細の取得',
            'description' => '指定したアプリ設定項目の現在値・デフォルト値・説明を取得する',
        ],
        [
            'name' => 'update-app-setting',
            'display_name' => 'アプリ設定の編集',
            'description' => '既存のアプリ設定項目の値を変更する',
        ],
        [
            'name' => 'delete-app-setting',
            'display_name' => 'アプリ設定の削除',
            'description' => '指定したアプリ設定項目をDBから削除する（デフォルト値に戻る）',
        ],
        [
            'name' => 'reset-app-setting-order',
            'display_name' => 'アプリ設定の順序リセット',
            'description' => 'アプリ設定項目の表示順序をデフォルトに戻す',
        ],
        [
            'name' => 'update-app-setting-order',
            'display_name' => 'アプリ設定の順序変更',
            'description' => 'アプリ設定項目の表示順序をドラッグ&ドロップなどで変更する',
        ],
        [
            'name' => 'set-default-app-settings',
            'display_name' => 'アプリ設定のデフォルト化',
            'description' => '指定したアプリ設定セットをシステムのデフォルトとして設定する',
        ],
    ],

    // ============================================================
    // AppRelease - アプリリリース管理
    // ============================================================
    'AppRelease' => [
        [
            'name' => 'get-app-release',
            'display_name' => 'アプリリリース情報の取得',
            'description' => 'iOS/Androidアプリの最新バージョン・リリースノート・更新日時を取得する',
        ],
        [
            'name' => 'update-app-release',
            'display_name' => 'アプリリリース情報の更新',
            'description' => 'アプリの最新バージョン番号・リリースノート・強制アップデート設定を登録・更新する',
        ],
    ],

    // ============================================================
    // AishipProduct - Aiship商品管理
    // ============================================================
    'AishipProduct' => [
        [
            'name' => 'aiship-list-product',
            'display_name' => 'Aiship商品一覧の取得',
            'description' => 'Aiship ECプラットフォームの全商品を一覧表示する（在庫・価格含む）',
        ],
        [
            'name' => 'aiship-find-product',
            'display_name' => 'Aiship商品詳細の取得',
            'description' => '指定したAiship商品の名前・価格・在庫・画像・説明などを取得する',
        ],
        [
            'name' => 'aiship-create-product',
            'display_name' => 'Aiship商品の新規作成',
            'description' => '新しいAiship商品（名前・価格・在庫・画像・説明）を登録する',
        ],
        [
            'name' => 'aiship-update-product',
            'display_name' => 'Aiship商品情報の編集',
            'description' => '既存のAiship商品の名前・価格・在庫・画像・説明を変更する',
        ],
        [
            'name' => 'aiship-delete-product',
            'display_name' => 'Aiship商品の削除',
            'description' => '指定したAiship商品をDBから削除する',
        ],
        [
            'name' => 'aiship-upload-product',
            'display_name' => 'Aiship商品画像のアップロード',
            'description' => 'Aiship商品用の画像ファイルをサーバーにアップロードする',
        ],
    ],

    // ============================================================
    // GroupPost - グループ投稿管理
    // ============================================================
    'GroupPost' => [
        [
            'name' => 'create-group-post',
            'display_name' => 'グループ投稿の新規作成',
            'description' => '指定したグループに新しい投稿（テキスト・画像・添付ファイル）を作成する',
        ],
        [
            'name' => 'delete-group-post',
            'display_name' => 'グループ投稿の削除',
            'description' => '指定したグループ投稿をDBから削除する（管理者権限でメンバー投稿も削除可能）',
        ],
        [
            'name' => 'find-group-post',
            'display_name' => 'グループ投稿詳細の取得',
            'description' => '指定したグループ投稿の本文・画像・投稿者・コメント数・リアクション数を取得する',
        ],
        [
            'name' => 'list-group-post',
            'display_name' => 'グループ投稿一覧の取得',
            'description' => '指定したグループの全投稿をページネーション付きで一覧表示する',
        ],
        [
            'name' => 'update-group-post',
            'display_name' => 'グループ投稿の編集',
            'description' => '既存のグループ投稿の本文・画像を変更する',
        ],
    ],

    // ============================================================
    // NotificationPush - プッシュ通知管理
    // ============================================================
    'NotificationPush' => [
        [
            'name' => 'create-notification-push',
            'display_name' => 'プッシュ通知の作成',
            'description' => '新しいプッシュ通知（タイトル・本文・対象ユーザー・配信日時）を作成・予約する',
        ],
        [
            'name' => 'update-notification-push',
            'display_name' => 'プッシュ通知の編集',
            'description' => '既存のプッシュ通知のタイトル・本文・配信日時を変更する（未配信のみ）',
        ],
        [
            'name' => 'delete-notification-push',
            'display_name' => 'プッシュ通知の削除',
            'description' => '指定したプッシュ通知をDBから削除する（未配信の予約通知をキャンセル）',
        ],
        [
            'name' => 'find-notification-push',
            'display_name' => 'プッシュ通知詳細の取得',
            'description' => '指定したプッシュ通知のタイトル・本文・対象ユーザー・配信状況を取得する',
        ],
        [
            'name' => 'list-notification-pushs',
            'display_name' => 'プッシュ通知一覧の取得',
            'description' => '全プッシュ通知をページネーション付きで一覧表示する（配信済み・予約中でフィルタ可能）',
        ],
        [
            'name' => 'update-fcm-token-receive-notification',
            'display_name' => '通知受信設定の変更',
            'description' => 'ユーザーのプッシュ通知受信ON/OFFをデバイス単位で変更する',
        ],
    ],

    // ============================================================
    // AppPage - アプリページ管理
    // ============================================================
    'AppPage' => [
        [
            'name' => 'list-app-pages',
            'display_name' => 'アプリページ一覧の取得',
            'description' => 'アプリ内のカスタムページ（利用規約・プライバシーポリシーなど）を一覧表示する',
        ],
        [
            'name' => 'find-app-page',
            'display_name' => 'アプリページ詳細の取得',
            'description' => '指定したアプリページのタイトル・本文・公開状態を取得する',
        ],
        [
            'name' => 'create-app-page',
            'display_name' => 'アプリページの新規作成',
            'description' => '新しいアプリ内カスタムページ（タイトル・本文・スラッグ）を作成する',
        ],
        [
            'name' => 'update-app-page',
            'display_name' => 'アプリページの編集',
            'description' => '既存のアプリページのタイトル・本文・公開状態を変更する',
        ],
        [
            'name' => 'delete-app-page',
            'display_name' => 'アプリページの削除',
            'description' => '指定したアプリページをDBから削除する',
        ],
    ],

    // ============================================================
    // FcmToken - FCMトークン管理
    // ============================================================
    'FcmToken' => [
        [
            'name' => 'list-fcm-tokens',
            'display_name' => 'FCMトークン一覧の取得',
            'description' => '指定ユーザーの全デバイスFCMトークン（デバイス名・OS・登録日時）を一覧表示する',
        ],
        [
            'name' => 'update-fcm-token-status',
            'display_name' => 'FCMトークン有効化/無効化',
            'description' => '指定したFCMトークンの有効/無効を切り替える（特定デバイスへの通知を停止）',
        ],
    ],

    // ============================================================
    // Member - メンバー管理
    // ============================================================
    'Member' => [
        [
            'name' => 'list-member',
            'display_name' => 'メンバー一覧の取得',
            'description' => 'メンバー（会員）を一覧表示する（会員番号・登録日含む）',
        ],
        [
            'name' => 'detail-member',
            'display_name' => 'メンバー詳細の取得',
            'description' => '指定したメンバーの会員情報・利用履歴・ポイント残高などを取得する',
        ],
        [
            'name' => 'update-member',
            'display_name' => 'メンバー情報の編集',
            'description' => '既存のメンバーの会員情報・ステータスを変更する',
        ],
        [
            'name' => 'member-list-reservations',
            'display_name' => 'メンバー予約一覧の取得',
            'description' => '会員の予約一覧を閲覧する権限',
        ],
        [
            'name' => 'member-detail-plan',
            'display_name' => 'メンバープラン詳細の取得',
            'description' => '会員の現在/次月プラン情報を閲覧する権限',
        ],
        [
            'name' => 'member-update-plan',
            'display_name' => 'メンバープランの更新',
            'description' => '会員の支払いステータスを更新する権限',
        ],
    ],

    // ============================================================
    // Location - ロケーション管理
    // ============================================================
    'Location' => [
        [
            'name' => 'list-location',
            'display_name' => 'ロケーション一覧の取得',
            'description' => '全ロケーション・施設を一覧表示する（住所・営業時間含む）',
        ],
        [
            'name' => 'find-location',
            'display_name' => 'ロケーション詳細の取得',
            'description' => '指定したロケーションの住所・営業時間・設備・写真などの詳細情報を取得する',
        ],
    ],

    // ============================================================
    // GolphLocation - ゴルフロケーション管理
    // ============================================================
    'GolphLocation' => [
        [
            'name' => 'golph-update-location',
            'display_name' => 'ゴルフロケーションの編集',
            'description' => 'ゴルフ施設の情報（コース情報・料金・営業時間）を変更する',
        ],
    ],

    // ============================================================
    // SaunaLocation - サウナロケーション管理
    // ============================================================
    'SaunaLocation' => [
        [
            'name' => 'sauna-update-location',
            'display_name' => 'サウナロケーションの編集',
            'description' => 'サウナ施設の情報（設備・料金・営業時間）を変更する',
        ],
    ],

    // ============================================================
    // Coupon - クーポン管理
    // ============================================================
    'Coupon' => [
        [
            'name' => 'golph-list-coupon',
            'display_name' => 'ゴルフクーポン一覧の取得',
            'description' => 'ゴルフ用の全クーポンを一覧表示する（割引率・有効期限含む）',
        ],
        [
            'name' => 'golph-create-coupon',
            'display_name' => 'ゴルフクーポンの作成',
            'description' => '新しいゴルフクーポン（コード・割引率・有効期限・利用条件）を作成する',
        ],
        [
            'name' => 'golph-detail-coupon',
            'display_name' => 'ゴルフクーポン詳細の取得',
            'description' => '指定したゴルフクーポンのコード・割引内容・利用状況などを取得する',
        ],
        [
            'name' => 'golph-update-coupon',
            'display_name' => 'ゴルフクーポンの編集',
            'description' => '既存のゴルフクーポンの割引率・有効期限・利用条件を変更する',
        ],
        [
            'name' => 'golph-delete-coupon',
            'display_name' => 'ゴルフクーポンの削除',
            'description' => '指定したゴルフクーポンをDBから削除し、利用不可にする',
        ],
        [
            'name' => 'sauna-list-coupon',
            'display_name' => 'サウナクーポン一覧の取得',
            'description' => 'サウナ用の全クーポンを一覧表示する（割引率・有効期限含む）',
        ],
        [
            'name' => 'sauna-create-coupon',
            'display_name' => 'サウナクーポンの作成',
            'description' => '新しいサウナクーポン（コード・割引率・有効期限・利用条件）を作成する',
        ],
        [
            'name' => 'sauna-detail-coupon',
            'display_name' => 'サウナクーポン詳細の取得',
            'description' => '指定したサウナクーポンのコード・割引内容・利用状況などを取得する',
        ],
        [
            'name' => 'sauna-update-coupon',
            'display_name' => 'サウナクーポンの編集',
            'description' => '既存のサウナクーポンの割引率・有効期限・利用条件を変更する',
        ],
        [
            'name' => 'sauna-delete-coupon',
            'display_name' => 'サウナクーポンの削除',
            'description' => '指定したサウナクーポンをDBから削除し、利用不可にする',
        ],
    ],

    // ============================================================
    // Term - 利用規約管理
    // ============================================================
    'Term' => [
        [
            'name' => 'term-detail',
            'display_name' => '利用規約の取得',
            'description' => '現在の利用規約本文・バージョンを取得する',
        ],
        [
            'name' => 'term-update',
            'display_name' => '利用規約の更新',
            'description' => '利用規約本文を変更・バージョンアップする',
        ],
    ],

    // ============================================================
    // GolphPlan - ゴルフプラン管理
    // ============================================================
    'GolphPlan' => [
        [
            'name' => 'golph-list-plan',
            'display_name' => 'ゴルフプラン一覧の取得',
            'description' => 'ゴルフ施設のプラン（コース・料金・時間帯）を一覧表示する',
        ],
        [
            'name' => 'golph-create-plan',
            'display_name' => 'ゴルフプランの作成',
            'description' => '新しいゴルフプラン（コース名・料金・所要時間・定員）を作成する',
        ],
        [
            'name' => 'golph-detail-plan',
            'display_name' => 'ゴルフプラン詳細の取得',
            'description' => '指定したゴルフプランのコース詳細・料金・予約状況などを取得する',
        ],
        [
            'name' => 'golph-update-plan',
            'display_name' => 'ゴルフプランの編集',
            'description' => '既存のゴルフプランのコース名・料金・定員などを変更する',
        ],
        [
            'name' => 'golph-delete-plan',
            'display_name' => 'ゴルフプランの削除',
            'description' => '指定したゴルフプランをDBから削除する',
        ],
    ],

    // ============================================================
    // AvailabilitySlot - 空き枠管理
    // ============================================================
    'AvailabilitySlot' => [
        [
            'name' => 'golph-list-availability-slot',
            'display_name' => 'ゴルフ空き枠一覧の取得',
            'description' => 'ゴルフ施設の予約枠スケジュールをカレンダー形式で一覧表示する',
        ],
        [
            'name' => 'golph-detail-availability-slot',
            'display_name' => 'ゴルフ空き枠詳細の取得',
            'description' => '指定した日時の予約枠の空き状況・料金・プランなどを取得する',
        ],
        [
            'name' => 'golph-update-availability-slot',
            'display_name' => 'ゴルフ空き枠の編集',
            'description' => '既存の予約枠スケジュールの時間帯・定員・料金を変更する',
        ],
        [
            'name' => 'golph-download-template',
            'display_name' => 'ゴルフ空き枠テンプレートDL',
            'description' => 'スケジュール一括登録用のCSVテンプレートファイルをダウンロードする',
        ],
        [
            'name' => 'golph-import-availability-slot',
            'display_name' => 'ゴルフ空き枠のインポート',
            'description' => 'CSVファイルからスケジュール・予約枠を一括でインポート・登録する',
        ],
        [
            'name' => 'sauna-list-availability-slot',
            'display_name' => 'サウナ空き枠一覧の取得',
            'description' => 'サウナ施設の予約枠スケジュールをカレンダー形式で一覧表示する',
        ],
        [
            'name' => 'sauna-detail-availability-slot',
            'display_name' => 'サウナ空き枠詳細の取得',
            'description' => '指定した日時のサウナ予約枠の空き状況・料金などを取得する',
        ],
        [
            'name' => 'sauna-update-availability-slot',
            'display_name' => 'サウナ空き枠の編集',
            'description' => '既存のサウナ予約枠スケジュールの時間帯・定員・料金を変更する',
        ],
        [
            'name' => 'sauna-import-availability-slot',
            'display_name' => 'サウナ空き枠のインポート',
            'description' => 'CSVファイルからサウナスケジュール・予約枠を一括でインポート・登録する',
        ],
        [
            'name' => 'sauna-download-template',
            'display_name' => 'サウナ空き枠テンプレートDL',
            'description' => 'サウナスケジュール一括登録用のCSVテンプレートファイルをダウンロードする',
        ],
    ],

    // ============================================================
    // Reservation - 予約管理
    // ============================================================
    'Reservation' => [
        [
            'name' => 'golph-list-reservation',
            'display_name' => 'ゴルフ予約一覧の取得',
            'description' => 'ゴルフの予約一覧を表示する権限',
        ],
        [
            'name' => 'golph-detail-reservation',
            'display_name' => 'ゴルフ予約詳細の取得',
            'description' => 'ゴルフの予約詳細を表示する権限',
        ],
        [
            'name' => 'golph-cancel-reservation',
            'display_name' => 'ゴルフ予約キャンセル',
            'description' => 'ゴルフの予約をキャンセルする権限',
        ],
        [
            'name' => 'golph-cancel-reservation-in-db',
            'display_name' => 'ゴルフ予約キャンセル（DB）',
            'description' => 'ゴルフの予約をDBからキャンセルする権限',
        ],
        [
            'name' => 'sauna-list-reservation',
            'display_name' => 'サウナ予約一覧の取得',
            'description' => 'サウナの予約一覧を表示する権限',
        ],
        [
            'name' => 'sauna-detail-reservation',
            'display_name' => 'サウナ予約詳細の取得',
            'description' => 'サウナの予約詳細を表示する権限',
        ],
        [
            'name' => 'sauna-cancel-reservation',
            'display_name' => 'サウナ予約キャンセル',
            'description' => 'サウナの予約をキャンセルする権限',
        ],
        [
            'name' => 'sauna-cancel-reservation-in-db',
            'display_name' => 'サウナ予約キャンセル（DB）',
            'description' => 'サウナの予約をDBからキャンセルする権限',
        ],
    ],

    // ============================================================
    // Faq - FAQ管理
    // ============================================================
    'Faq' => [
        [
            'name' => 'create-faqs',
            'display_name' => 'FAQの新規作成',
            'description' => '新しいFAQ項目（質問・回答・カテゴリ・表示順）を作成する',
        ],
        [
            'name' => 'delete-faqs',
            'display_name' => 'FAQの削除',
            'description' => '指定したFAQ項目をDBから削除する',
        ],
        [
            'name' => 'view-faqs',
            'display_name' => 'FAQ一覧の取得',
            'description' => '全FAQ項目を表示順に一覧表示する（カテゴリ・検索キーワードでフィルタ可能）',
        ],
        [
            'name' => 'update-faqs',
            'display_name' => 'FAQの編集',
            'description' => '既存のFAQ項目の質問・回答・カテゴリ・表示順を変更する',
        ],
    ],

    // ============================================================
    // StripeAccount - Stripeアカウント管理
    // ============================================================
    'StripeAccount' => [
        [
            'name' => 'create-stripe-account',
            'display_name' => 'Stripeアカウントの登録',
            'description' => '新しいStripe連携アカウント（APIキー・Webhook Secret・テストモード設定）を登録する',
        ],
        [
            'name' => 'delete-stripe-account',
            'display_name' => 'Stripeアカウントの削除',
            'description' => '指定したStripeアカウント連携をDBから削除する（決済は不可になる）',
        ],
        [
            'name' => 'find-stripe-account',
            'display_name' => 'Stripeアカウント詳細の取得',
            'description' => '指定したStripeアカウントのAPIキー・ステータス・売上統計などを取得する',
        ],
        [
            'name' => 'list-stripe-account',
            'display_name' => 'Stripeアカウント一覧の取得',
            'description' => '全Stripe連携アカウントを一覧表示する（本番/テスト・有効/無効でフィルタ可能）',
        ],
        [
            'name' => 'update-stripe-account',
            'display_name' => 'Stripeアカウントの編集',
            'description' => '既存のStripeアカウントのAPIキー・Webhook Secret・有効/無効を変更する',
        ],
    ],

    // ============================================================
    // Columns - カラム（コンテンツ）管理
    // ============================================================
    'Columns' => [
        [
            'name' => 'create-columns',
            'display_name' => 'コラムの作成',
            'description' => '新しいコラム記事（タイトル・本文・価格・サムネイル）を作成する',
        ],
        [
            'name' => 'delete-columns',
            'display_name' => 'コラムの削除',
            'description' => '指定したコラム記事をDBから削除する',
        ],
        [
            'name' => 'find-columns',
            'display_name' => 'コラム詳細の取得',
            'description' => '指定したコラムのタイトル・本文・価格・購入者数などを取得する',
        ],
        [
            'name' => 'list-columns',
            'display_name' => 'コラム一覧の取得',
            'description' => '全コラム記事をページネーション付きで一覧表示する',
        ],
        [
            'name' => 'update-columns',
            'display_name' => 'コラムの編集',
            'description' => '既存のコラム記事のタイトル・本文・価格・公開状態を変更する',
        ],
    ],

    // ============================================================
    // Comments - コメント管理
    // ============================================================
    'Comments' => [
        [
            'name' => 'delete-comment',
            'display_name' => 'コメントの削除',
            'description' => '指定したコメント（投稿・コラムなどへのコメント）をDBから削除する（管理者モデレーション用）',
        ],
        [
            'name' => 'list-comment',
            'display_name' => 'コメント一覧の取得',
            'description' => '全コメントをページネーション付きで一覧表示する（投稿者・日付・コンテンツでフィルタ可能）',
        ],
    ],

    // ============================================================
    // PaymentTrigger - 決済トリガー管理
    // ============================================================
    'PaymentTrigger' => [
        [
            'name' => 'view-payment-triggers',
            'display_name' => '決済トリガー一覧の取得',
            'description' => '決済完了時に実行されるトリガー（メンバーシップ付与・メール送信など）を一覧表示する',
        ],
        [
            'name' => 'edit-payment-triggers',
            'display_name' => '決済トリガーの編集',
            'description' => '決済トリガーの実行条件・アクション内容を変更する',
        ],
    ],

    // ============================================================
    // Product - 商品管理
    // ============================================================
    'Product' => [
        [
            'name' => 'view-products',
            'display_name' => '商品一覧の取得',
            'description' => '全商品をページネーション付きで一覧表示する（価格・在庫・カテゴリでフィルタ可能）',
        ],
        [
            'name' => 'create-products',
            'display_name' => '商品の作成',
            'description' => '新しい商品（名前・価格・説明・画像・メンバーシップ紐付け）を作成する',
        ],
        [
            'name' => 'edit-products',
            'display_name' => '商品の編集',
            'description' => '既存の商品の名前・価格・説明・画像・公開状態を変更する',
        ],
        [
            'name' => 'delete-products',
            'display_name' => '商品の削除',
            'description' => '指定した商品をDBから削除する（購入済みユーザーには影響しない）',
        ],
    ],

    // ============================================================
    // Maintenance - メンテナンス管理
    // ============================================================
    'Maintenance' => [
        [
            'name' => 'export-erd',
            'display_name' => 'ERD図のエクスポート',
            'description' => 'データベースのER図（Entity-Relationship Diagram）を画像/PDFでエクスポートする',
        ],
        [
            'name' => 'database-explorer.view',
            'display_name' => 'データベース探索',
            'description' => 'DBの全テーブル一覧を表示し、各テーブルのレコードを閲覧する（開発・調査用）',
        ],
        [
            'name' => 'system-settings.view',
            'display_name' => 'システム設定の閲覧',
            'description' => 'メンテナンス用のシステム設定項目を一覧表示する',
        ],
        [
            'name' => 'system-settings.create',
            'display_name' => 'システム設定の作成',
            'description' => '新しいシステム設定項目（キー・値・説明）を作成する',
        ],
        [
            'name' => 'system-settings.update',
            'display_name' => 'システム設定の変更',
            'description' => '既存のシステム設定項目の値を変更する（システム全体に即時反映）',
        ],
        [
            'name' => 'system-settings.delete',
            'display_name' => 'システム設定の削除',
            'description' => '指定したシステム設定項目をDBから削除する',
        ],
    ],

    // ============================================================
    // AiApplication - AIアプリケーション管理
    // ============================================================
    'AiApplication' => [
        [
            'name' => 'create-ai-application',
            'display_name' => 'AIアプリの新規作成',
            'description' => '新しいAIアプリケーション（名前・設定・APIキー）を作成する',
        ],
        [
            'name' => 'delete-ai-application',
            'display_name' => 'AIアプリの削除',
            'description' => '指定したAIアプリケーションをDBから削除する',
        ],
        [
            'name' => 'find-ai-application',
            'display_name' => 'AIアプリ詳細の取得',
            'description' => '指定したAIアプリケーションの設定・使用状況などを取得する',
        ],
        [
            'name' => 'list-ai-application',
            'display_name' => 'AIアプリ一覧の取得',
            'description' => '全AIアプリケーションを一覧表示する',
        ],
        [
            'name' => 'update-ai-application',
            'display_name' => 'AIアプリの編集',
            'description' => '既存のAIアプリケーションの設定を変更する',
        ],
        [
            'name' => 'generate-ai-application-api-key',
            'display_name' => 'AIアプリAPIキー生成',
            'description' => 'AIアプリケーション用の新しいAPIキーを生成する',
        ],
        [
            'name' => 'toggle-ai-application-active',
            'display_name' => 'AIアプリ有効化/無効化',
            'description' => 'AIアプリケーションの有効/無効を切り替える',
        ],
    ],

    // ============================================================
    // AiModelPrice - AIモデル料金管理
    // ============================================================
    'AiModelPrice' => [
        [
            'name' => 'create-ai-model-price',
            'display_name' => 'AIモデル料金の作成',
            'description' => '新しいAIモデル料金設定（モデル名・入力単価・出力単価）を作成する',
        ],
        [
            'name' => 'delete-ai-model-price',
            'display_name' => 'AIモデル料金の削除',
            'description' => '指定したAIモデル料金設定をDBから削除する',
        ],
        [
            'name' => 'find-ai-model-price',
            'display_name' => 'AIモデル料金詳細の取得',
            'description' => '指定したAIモデルの料金設定を取得する',
        ],
        [
            'name' => 'list-ai-model-price',
            'display_name' => 'AIモデル料金一覧の取得',
            'description' => '全AIモデル料金設定を一覧表示する',
        ],
        [
            'name' => 'update-ai-model-price',
            'display_name' => 'AIモデル料金の編集',
            'description' => '既存のAIモデル料金設定を変更する',
        ],
    ],

    // ============================================================
    // AiProvider - AIプロバイダー管理
    // ============================================================
    'AiProvider' => [
        [
            'name' => 'create-ai-provider',
            'display_name' => 'AIプロバイダーの作成',
            'description' => '新しいAIプロバイダー（OpenAI・Anthropicなど）の接続設定を作成する',
        ],
        [
            'name' => 'delete-ai-provider',
            'display_name' => 'AIプロバイダーの削除',
            'description' => '指定したAIプロバイダー設定をDBから削除する',
        ],
        [
            'name' => 'find-ai-provider',
            'display_name' => 'AIプロバイダー詳細の取得',
            'description' => '指定したAIプロバイダーの設定・使用状況を取得する',
        ],
        [
            'name' => 'list-ai-provider',
            'display_name' => 'AIプロバイダー一覧の取得',
            'description' => '全AIプロバイダー設定を一覧表示する',
        ],
        [
            'name' => 'update-ai-provider',
            'display_name' => 'AIプロバイダーの編集',
            'description' => '既存のAIプロバイダーの設定を変更する',
        ],
        [
            'name' => 'list-ai-provider-models',
            'display_name' => 'AIモデル一覧の取得',
            'description' => '指定したAIプロバイダーで利用可能なモデル一覧を取得する',
        ],
    ],

    // ============================================================
    // Group - グループ管理
    // ============================================================
    'Group' => [
        [
            'name' => 'create-group',
            'display_name' => 'グループの新規作成',
            'description' => '新しいグループ（名前・説明・公開設定）を作成する',
        ],
        [
            'name' => 'delete-group',
            'display_name' => 'グループの削除',
            'description' => '指定したグループをDBから削除する',
        ],
        [
            'name' => 'find-group',
            'display_name' => 'グループ詳細の取得',
            'description' => '指定したグループの設定・メンバー情報を取得する',
        ],
        [
            'name' => 'list-group',
            'display_name' => 'グループ一覧の取得',
            'description' => '全グループを一覧表示する',
        ],
        [
            'name' => 'update-group',
            'display_name' => 'グループの編集',
            'description' => '既存のグループの設定を変更する',
        ],
        [
            'name' => 'manage-group-users',
            'display_name' => 'グループメンバー管理',
            'description' => 'グループメンバーの追加・削除・権限変更を行う',
        ],
        [
            'name' => 'manage-group-access',
            'display_name' => 'グループアクセス設定',
            'description' => 'グループのアクセス権限設定を管理する',
        ],
    ],

    // ============================================================
    // Memberships - メンバーシップ管理
    // ============================================================
    'Memberships' => [
        [
            'name' => 'create-membership',
            'display_name' => 'メンバーシップの作成',
            'description' => '新しいメンバーシップ（名前・特典・料金）を作成する',
        ],
        [
            'name' => 'delete-membership',
            'display_name' => 'メンバーシップの削除',
            'description' => '指定したメンバーシップをDBから削除する',
        ],
        [
            'name' => 'find-membership',
            'display_name' => 'メンバーシップ詳細の取得',
            'description' => '指定したメンバーシップの設定・統計情報を取得する',
        ],
        [
            'name' => 'list-membership',
            'display_name' => 'メンバーシップ一覧の取得',
            'description' => '全メンバーシップを一覧表示する',
        ],
        [
            'name' => 'update-membership',
            'display_name' => 'メンバーシップの編集',
            'description' => '既存のメンバーシップの設定を変更する',
        ],
        [
            'name' => 'manage-membership-permissions',
            'display_name' => 'メンバーシップ権限管理',
            'description' => 'メンバーシップに紐付く権限を管理する',
        ],
        [
            'name' => 'manage-membership-roles',
            'display_name' => 'メンバーシップロール管理',
            'description' => 'メンバーシップに紐付くロールを管理する',
        ],
        [
            'name' => 'manage-membership-plans',
            'display_name' => 'メンバーシッププラン管理',
            'description' => 'メンバーシップに紐付くプランを管理する',
        ],
        [
            'name' => 'manage-membership-users',
            'display_name' => 'メンバーシップユーザー管理',
            'description' => 'メンバーシップに所属するユーザーを管理する',
        ],
    ],

    // ============================================================
    // CommunityPost - コミュニティ投稿管理
    // ============================================================
    'CommunityPost' => [
        [
            'name' => 'create-community-post',
            'display_name' => 'コミュニティ投稿の作成',
            'description' => '新しいコミュニティ投稿を作成する',
        ],
        [
            'name' => 'delete-community-post',
            'display_name' => 'コミュニティ投稿の削除',
            'description' => '指定したコミュニティ投稿をDBから削除する',
        ],
        [
            'name' => 'find-community-post',
            'display_name' => 'コミュニティ投稿詳細の取得',
            'description' => '指定したコミュニティ投稿の詳細を取得する',
        ],
        [
            'name' => 'list-community-post',
            'display_name' => 'コミュニティ投稿一覧の取得',
            'description' => '全コミュニティ投稿を一覧表示する',
        ],
        [
            'name' => 'update-community-post',
            'display_name' => 'コミュニティ投稿の編集',
            'description' => '既存のコミュニティ投稿を変更する',
        ],
    ],

    // ============================================================
    // MyPost - 自己投稿管理
    // ============================================================
    'MyPost' => [
        [
            'name' => 'create-my-post',
            'display_name' => '投稿の新規作成',
            'description' => '新しい投稿（テキスト・画像）を作成する',
        ],
        [
            'name' => 'delete-my-post',
            'display_name' => '投稿の削除',
            'description' => '指定した投稿をDBから削除する',
        ],
        [
            'name' => 'find-my-post',
            'display_name' => '投稿詳細の取得',
            'description' => '指定した投稿の詳細を取得する',
        ],
        [
            'name' => 'list-my-post',
            'display_name' => '投稿一覧の取得',
            'description' => '自分の投稿を一覧表示する',
        ],
        [
            'name' => 'update-my-post',
            'display_name' => '投稿の編集',
            'description' => '既存の投稿を変更する',
        ],
        [
            'name' => 'view-timeline',
            'display_name' => 'タイムラインの閲覧',
            'description' => 'フォロー中ユーザーの投稿タイムラインを閲覧する',
        ],
    ],

    // ============================================================
    // Schedule - スケジュール（ユーザー向け）
    // ============================================================
    'UserSchedule' => [
        [
            'name' => 'create-user-schedule',
            'display_name' => 'スケジュール作成',
            'description' => '個人のスケジュール・予定を作成する',
        ],
        [
            'name' => 'delete-user-schedule',
            'display_name' => 'スケジュール削除',
            'description' => '個人のスケジュール・予定を削除する',
        ],
        [
            'name' => 'find-user-schedule',
            'display_name' => 'スケジュール詳細取得',
            'description' => '個人のスケジュール詳細を取得する',
        ],
        [
            'name' => 'list-user-schedule',
            'display_name' => 'スケジュール一覧取得',
            'description' => '個人のスケジュール一覧を取得する',
        ],
        [
            'name' => 'update-user-schedule',
            'display_name' => 'スケジュール編集',
            'description' => '個人のスケジュール・予定を編集する',
        ],
    ],

    // ============================================================
    // VideoCall - ビデオ通話
    // ============================================================
    'VideoCall' => [
        [
            'name' => 'initiate-video-call',
            'display_name' => 'ビデオ通話開始',
            'description' => '他のユーザーへのビデオ通話を開始する',
        ],
        [
            'name' => 'accept-video-call',
            'display_name' => 'ビデオ通話応答',
            'description' => '着信したビデオ通話に応答する',
        ],
        [
            'name' => 'reject-video-call',
            'display_name' => 'ビデオ通話拒否',
            'description' => '着信したビデオ通話を拒否する',
        ],
        [
            'name' => 'end-video-call',
            'display_name' => 'ビデオ通話終了',
            'description' => '進行中のビデオ通話を終了する',
        ],
    ],

    // ============================================================
    // DatabaseLog - データベースログ管理
    // ============================================================
    'DatabaseLog' => [
        [
            'name' => 'view-database-log',
            'display_name' => 'データベースログ閲覧',
            'description' => 'データベース操作の監査ログを閲覧する',
        ],
        [
            'name' => 'export-database-log',
            'display_name' => 'データベースログ出力',
            'description' => 'データベースログをCSV/JSON形式でエクスポートする',
        ],
    ],

    // ============================================================
    // EmailNotification - メール通知設定
    // ============================================================
    'EmailNotification' => [
        [
            'name' => 'view-email-notification',
            'display_name' => 'メール通知設定閲覧',
            'description' => 'メール通知の設定内容を閲覧する',
        ],
        [
            'name' => 'update-email-notification',
            'display_name' => 'メール通知設定編集',
            'description' => 'メール通知の設定を変更する',
        ],
    ],

    // ============================================================
    // UserPurchasedPlan - ユーザー購入プラン管理
    // ============================================================
    'UserPurchasedPlan' => [
        [
            'name' => 'attach-plan-to-user',
            'display_name' => 'ユーザーへプラン付与',
            'description' => 'ユーザーにプランを手動で付与する',
        ],
        [
            'name' => 'detach-plan-from-user',
            'display_name' => 'ユーザーからプラン削除',
            'description' => 'ユーザーからプランを削除する',
        ],
        [
            'name' => 'view-user-plans',
            'display_name' => 'ユーザープラン閲覧',
            'description' => 'ユーザーが購入したプランを閲覧する',
        ],
    ],

    // ============================================================
    // UserPurchasedProduct - ユーザー購入商品管理
    // ============================================================
    'UserPurchasedProduct' => [
        [
            'name' => 'attach-product-to-user',
            'display_name' => 'ユーザーへ商品付与',
            'description' => 'ユーザーに商品を手動で付与する',
        ],
        [
            'name' => 'detach-product-from-user',
            'display_name' => 'ユーザーから商品削除',
            'description' => 'ユーザーから商品を削除する',
        ],
        [
            'name' => 'view-user-products',
            'display_name' => 'ユーザー商品閲覧',
            'description' => 'ユーザーが購入した商品を閲覧する',
        ],
    ],

    // ============================================================
    // UserMemberships - ユーザーメンバーシップ管理
    // ============================================================
    'UserMemberships' => [
        [
            'name' => 'view-user-memberships',
            'display_name' => 'ユーザーメンバーシップ閲覧',
            'description' => 'ユーザーが所属するメンバーシップを閲覧する',
        ],
        [
            'name' => 'sync-user-memberships',
            'display_name' => 'ユーザーメンバーシップ同期',
            'description' => 'ユーザーのメンバーシップを同期・更新する',
        ],
    ],

    // ============================================================
    // Follow - フォロー管理
    // ============================================================
    'Follow' => [
        [
            'name' => 'create-follow',
            'display_name' => 'フォロー',
            'description' => '他のユーザーをフォローする',
        ],
        [
            'name' => 'delete-follow',
            'display_name' => 'フォロー解除',
            'description' => 'フォロー中のユーザーを解除する',
        ],
        [
            'name' => 'view-follow-list',
            'display_name' => 'フォローリスト閲覧',
            'description' => 'フォロー中・フォロワーのリストを閲覧する',
        ],
    ],

    // ============================================================
    // Invite - 招待管理
    // ============================================================
    'Invite' => [
        [
            'name' => 'create-invite',
            'display_name' => '招待の作成',
            'description' => '新しい招待リンク・コードを作成する',
        ],
        [
            'name' => 'view-invites',
            'display_name' => '招待一覧の閲覧',
            'description' => '作成した招待の一覧を閲覧する',
        ],
    ],

    // ============================================================
    // Comment - コメント（ユーザー向け）
    // ============================================================
    'UserComment' => [
        [
            'name' => 'create-user-comment',
            'display_name' => 'コメント投稿',
            'description' => '投稿・コンテンツにコメントを投稿する',
        ],
        [
            'name' => 'view-user-comments',
            'display_name' => 'コメント閲覧',
            'description' => '投稿・コンテンツのコメントを閲覧する',
        ],
    ],

    // ============================================================
    // PostComment - 投稿コメント管理
    // ============================================================
    'PostComment' => [
        [
            'name' => 'create-post-comment',
            'display_name' => '投稿コメント作成',
            'description' => '投稿に対するコメントを作成する',
        ],
        [
            'name' => 'delete-post-comment',
            'display_name' => '投稿コメント削除',
            'description' => '投稿に対するコメントを削除する',
        ],
        [
            'name' => 'view-post-comments',
            'display_name' => '投稿コメント閲覧',
            'description' => '投稿のコメント一覧を閲覧する',
        ],
    ],

    // ============================================================
    // StripeTransaction - Stripe取引管理
    // ============================================================
    'StripeTransaction' => [
        [
            'name' => 'view-stripe-transactions',
            'display_name' => 'Stripe取引一覧',
            'description' => 'Stripeの取引履歴を一覧表示する',
        ],
        [
            'name' => 'export-stripe-transactions',
            'display_name' => 'Stripe取引エクスポート',
            'description' => 'Stripeの取引履歴をCSV形式でエクスポートする',
        ],
        [
            'name' => 'view-stripe-dashboard',
            'display_name' => 'Stripeダッシュボード',
            'description' => 'Stripeの売上統計ダッシュボードを閲覧する',
        ],
        [
            'name' => 'view-stripe-products',
            'display_name' => 'Stripe商品一覧',
            'description' => 'Stripeに登録された商品・価格を閲覧する',
        ],
        [
            'name' => 'test-stripe-connection',
            'display_name' => 'Stripe接続テスト',
            'description' => 'Stripe APIへの接続をテストする',
        ],
    ],

    // ============================================================
    // WorkManagement - ワーク管理（ユーザー向け）
    // ============================================================
    'UserWorkManagement' => [
        [
            'name' => 'view-user-work',
            'display_name' => 'ワーク履歴閲覧',
            'description' => '自分のワーク履歴を閲覧する',
        ],
        [
            'name' => 'create-work-reaction',
            'display_name' => 'ワークリアクション',
            'description' => 'ワークにリアクションを追加する',
        ],
    ],

    // ============================================================
    // Rank - ランク（ユーザー向け）
    // ============================================================
    'UserRank' => [
        [
            'name' => 'view-user-rank',
            'display_name' => 'ランク閲覧',
            'description' => '自分のランク・ポイントを閲覧する',
        ],
    ],

    // ============================================================
    // Scenario - シナリオ管理
    // ============================================================
    'Scenario' => [
        [
            'name' => 'list-scenarios',
            'display_name' => 'シナリオ一覧の取得',
            'description' => '全シナリオをページネーション付きで一覧表示する',
        ],
        [
            'name' => 'find-scenario',
            'display_name' => 'シナリオ詳細の取得',
            'description' => '指定したシナリオの詳細情報を取得する',
        ],
        [
            'name' => 'create-scenario',
            'display_name' => 'シナリオの新規作成',
            'description' => '新しいシナリオ（名前・説明・ステップ構成）を作成する',
        ],
        [
            'name' => 'update-scenario',
            'display_name' => 'シナリオの編集',
            'description' => '既存のシナリオの名前・説明・設定を変更する',
        ],
        [
            'name' => 'delete-scenario',
            'display_name' => 'シナリオの削除',
            'description' => '指定したシナリオをDBから削除する',
        ],
    ],
    // ============================================================
    // PusherInfo - プッシュ通知設定管理
    // ============================================================
    'PusherInfo' => [
        [
            'name' => 'list-pusher-info',
            'display_name' => 'プッシュ設定一覧の取得',
            'description' => 'Pusher/Firebase のプッシュ通知設定をページネーション付きで一覧表示する（検索・並び替え対応）',
        ],
        [
            'name' => 'find-pusher-info',
            'display_name' => 'プッシュ設定詳細の取得',
            'description' => '指定したプッシュ通知設定の詳細（push_type・Firebase情報・Pusher情報など）を取得する',
        ],
        [
            'name' => 'create-pusher-info',
            'display_name' => 'プッシュ設定の新規作成',
            'description' => 'プッシュ通知設定を新規作成する（Firebaseの場合は認証JSONファイルを登録、Pusherの場合は接続情報を登録）',
        ],
        [
            'name' => 'update-pusher-info',
            'display_name' => 'プッシュ設定の編集',
            'description' => '既存のプッシュ通知設定を更新する（Firebaseの認証JSONを差し替えた場合は旧ファイルを削除して更新する）',
        ],
        [
            'name' => 'delete-pusher-info',
            'display_name' => 'プッシュ設定の削除',
            'description' => '指定したプッシュ通知設定を削除する（必要に応じてFirebase認証ファイルも削除する）',
        ],
    ],
    // ============================================================
    // SignatureInfo - 署名情報管理
    // ============================================================
    'SignatureInfo' => [
        [
            'name' => 'list-signature-info',
            'display_name' => '署名情報一覧',
            'description' => '署名情報をページネーション付きで一覧表示する',
        ],
        [
            'name' => 'find-signature-info',
            'display_name' => '署名情報詳細',
            'description' => '署名情報の詳細を取得する',
        ],
        [
            'name' => 'create-signature-info',
            'display_name' => '署名情報作成',
            'description' => '署名情報を新規作成する',
        ],
        [
            'name' => 'update-signature-info',
            'display_name' => '署名情報更新',
            'description' => '署名情報を更新する',
        ],
        [
            'name' => 'delete-signature-info',
            'display_name' => '署名情報削除',
            'description' => '署名情報を削除する',
        ],
    ],
    // ============================================================
    // SystemAdmin - SSO設定管理
    // ============================================================
    'SystemAdmin' => [

        // ---------- SSO Domain ----------
        [
            'name' => 'list-systemadmin-sso-domain',
            'display_name' => 'SSOドメイン一覧',
            'description' => 'System Admin 用のSSOクライアントドメインを一覧で取得する',
        ],
        [
            'name' => 'find-systemadmin-sso-domain',
            'display_name' => 'SSOドメイン詳細',
            'description' => '指定したSystem Admin用SSOドメインの詳細情報を取得する',
        ],
        [
            'name' => 'create-systemadmin-sso-domain',
            'display_name' => 'SSOドメイン作成',
            'description' => 'System Admin用の新しいSSOクライアントドメインを登録する',
        ],
        [
            'name' => 'update-systemadmin-sso-domain',
            'display_name' => 'SSOドメイン更新',
            'description' => 'System Admin用SSOクライアントドメイン設定を更新する',
        ],
        [
            'name' => 'delete-systemadmin-sso-domain',
            'display_name' => 'SSOドメイン削除',
            'description' => 'System Admin用SSOクライアントドメインを削除する',
        ],

        // ---------- SSO Provider ----------
        [
            'name' => 'list-systemadmin-sso-provider',
            'display_name' => 'SSOプロバイダー一覧',
            'description' => 'SSOプロバイダー設定をページネーション付きで一覧表示する',
        ],
        [
            'name' => 'find-systemadmin-sso-provider',
            'display_name' => 'SSOプロバイダー詳細',
            'description' => '指定したSSOプロバイダー設定の詳細（provider_type・client_key・scope等）を取得する',
        ],
        [
            'name' => 'create-systemadmin-sso-provider',
            'display_name' => 'SSOプロバイダー作成',
            'description' => '新しいSSOプロバイダー設定を登録する',
        ],
        [
            'name' => 'update-systemadmin-sso-provider',
            'display_name' => 'SSOプロバイダー更新',
            'description' => '既存のSSOプロバイダー設定を更新する',
        ],
        [
            'name' => 'delete-systemadmin-sso-provider',
            'display_name' => 'SSOプロバイダー削除',
            'description' => 'SSOプロバイダー設定を削除する',
        ],
    ],
    // ============================================================
    // Entitlement - エンタイトルメント管理
    // ============================================================
    'Entitlement' => [
        [
            'name' => 'list-entitlement-types',
            'display_name' => 'エンタイトルメントタイプ一覧',
            'description' => '全エンタイトルメントタイプを一覧表示する',
        ],
        [
            'name' => 'find-entitlement-type',
            'display_name' => 'エンタイトルメントタイプ詳細',
            'description' => '指定したエンタイトルメントタイプの詳細を取得する',
        ],
        [
            'name' => 'create-entitlement-type',
            'display_name' => 'エンタイトルメントタイプ作成',
            'description' => '新しいエンタイトルメントタイプを作成する',
        ],
        [
            'name' => 'update-entitlement-type',
            'display_name' => 'エンタイトルメントタイプ編集',
            'description' => '既存のエンタイトルメントタイプを編集する',
        ],
        [
            'name' => 'delete-entitlement-type',
            'display_name' => 'エンタイトルメントタイプ削除',
            'description' => 'エンタイトルメントタイプを削除する',
        ],
        [
            'name' => 'view-user-entitlements',
            'display_name' => 'ユーザーエンタイトルメント閲覧',
            'description' => 'ユーザーのエンタイトルメントを閲覧する',
        ],
        [
            'name' => 'grant-user-entitlement',
            'display_name' => 'エンタイトルメント付与',
            'description' => 'ユーザーにエンタイトルメントを付与する',
        ],
        [
            'name' => 'override-user-entitlement',
            'display_name' => 'エンタイトルメントオーバーライド',
            'description' => 'ユーザーのエンタイトルメント値をオーバーライドする',
        ],
        [
            'name' => 'revoke-user-entitlement',
            'display_name' => 'エンタイトルメント剥奪',
            'description' => 'ユーザーからエンタイトルメントを剥奪する',
        ],
        [
            'name' => 'manage-membership-entitlements',
            'display_name' => 'メンバーシップエンタイトルメント管理',
            'description' => 'メンバーシップのエンタイトルメント設定を管理する',
        ],
    ],
];
