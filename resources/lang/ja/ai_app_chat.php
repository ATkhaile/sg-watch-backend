<?php

return [
    'application_not_found' => 'AIアプリケーションが見つかりません。',
    'provider_inactive' => 'AIプロバイダーが無効です。',
    'send_success' => 'メッセージを送信しました。',
    'conversation_not_found' => '会話が見つかりません。',
    'conversation_name_updated' => '会話名を更新しました。',
    'conversation_shared' => '会話を共有しました。',
    'conversation_unshared' => '会話の共有を解除しました。',
    'shared_conversation_not_found' => '共有された会話が見つからないか、共有が解除されています。',

    // Reasoning steps titles
    'reasoning_steps' => [
        'analyzing_request' => 'リクエストを分析中',
        'generating_final_response' => '最終回答を生成中',
        'processing' => '処理中',
        'received_tool_results' => 'ツール結果を受信',
        'completed_tool_executions' => ':success/:total のツール実行完了',

        // Tool call titles (when calling)
        'tool_call' => [
            'search_knowledge' => 'ナレッジベースを検索中',
            'generate_image' => '画像を生成中',
            'generate_website' => 'ウェブサイトを生成中',
            'fetch_url_content' => 'URLコンテンツを取得中',
            'get_current_user' => 'ユーザー情報を取得中',
            'code_interpreter' => 'コードを実行中',
            'web_search' => 'ウェブを検索中',
            'default' => ':tool_name を呼び出し中',
        ],

        // Tool result titles (when completed)
        'tool_result' => [
            'search_knowledge' => 'ナレッジ結果を取得',
            'generate_image' => '画像を生成完了',
            'generate_website' => 'ウェブサイトコードを生成完了',
            'fetch_url_content' => 'URLコンテンツを取得完了',
            'get_current_user' => 'ユーザー情報を取得完了',
            'code_interpreter' => 'コード実行完了',
            'web_search' => 'ウェブ検索完了',
            'default' => ':tool_name 完了',
        ],
    ],

    'validation' => [
        'message' => [
            'required' => 'メッセージは必須です。',
            'string' => 'メッセージは文字列である必要があります。',
            'max' => 'メッセージは10000文字以内で入力してください。',
        ],
        'conversation_id' => [
            'integer' => '会話IDは整数である必要があります。',
            'exists' => '指定された会話が存在しません。',
        ],
        'from_source' => [
            'string' => 'ソースは文字列である必要があります。',
            'max' => 'ソースは50文字以内で入力してください。',
        ],
        'files' => [
            'array' => 'ファイルは配列である必要があります。',
            'max' => 'アップロードできるファイルは最大5つまでです。',
        ],
        'files_item' => [
            'file' => '各項目は有効なファイルである必要があります。',
            'mimes' => 'ファイル形式は jpg, jpeg, png, gif, webp, pdf, doc, docx, xls, xlsx, ppt, pptx のいずれかである必要があります。',
            'max' => '各ファイルは20MB以内である必要があります。',
        ],
    ],
];
