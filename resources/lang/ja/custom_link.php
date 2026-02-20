<?php

return [

    'list' => [
        'success' => 'カスタムリンクの取得に成功しました。',
    ],
    'save' => [
        'success' => 'カスタムリンクを保存しました。',
        'failed'  => 'カスタムリンクの保存に失敗しました。',
    ],
    'create' => [
        'success' => 'カスタムリンクを作成しました。',
        'failed'  => 'カスタムリンクの作成に失敗しました。',
    ],
    'summary' => ['message' => 'サマリー取得に成功しました。'],
    'graph'   => ['message' => 'グラフデータ取得に成功しました。'],
    'history' => ['message' => 'アクセス履歴取得に成功しました。'],
    'validation' => [
        'custom_links' => [
            'required' => 'カスタムリンク配列は必須です。',
            'array'    => 'カスタムリンクは配列で指定してください。',
            'min'      => '少なくとも1件のカスタムリンクを指定してください。',
        ],

        'id' => [
            'required' => 'IDは必須です。',
            'integer' => 'IDは整数で指定してください。',
            'exists'  => '指定されたIDのカスタムリンクは存在しません。',
        ],

        'name' => [
            'required' => '名称は必須です。',
            'string'   => '名称は文字列で指定してください。',
            'max'      => '名称は:max文字以内で指定してください。',
        ],

        'prefix' => [
            'required' => 'プレフィックスは必須です。',
            'string'   => 'プレフィックスは文字列で指定してください。',
            'max'      => 'プレフィックスは:max文字以内で指定してください。',
            'unique'   => 'このプレフィックスは既に使用されています。',
            'exists'   => '選択したプレフィックスは存在しません。',
        ],

        'redirect_url' => [
            'required' => 'リダイレクトURLは必須です。',
            'string'   => 'リダイレクトURLは文字列で指定してください。',
            'max'      => 'リダイレクトURLは:max文字以内で指定してください。',
            'url'      => '有効なURL形式で指定してください。',
        ],

        'action' => [
            'required' => 'アクションは必須です。',
            'in'       => '選択されたアクションは無効です。',
        ],

        'order_num' => [
            'required' => '表示順は必須です。',
            'integer'  => '表示順は整数で指定してください。',
            'min'      => '表示順は:min以上で指定してください。',
        ],
        'view_type' => ['required' => 'view_typeは必須です。', 'in' => 'view_typeはyear|month|dayのみ。'],
        'year'  => ['integer' => 'yearは整数。'],
        'month' => ['regex' => 'monthはYYYY/MM形式。'],
        'day'   => ['regex' => 'dayはYYYY/MM/DD形式。'],
        'page'  => ['integer' => 'ページは整数。'],
        'limit' => ['integer' => 'リミットは整数。'],
        'from'  => ['date_format' => 'fromはY/m/d形式。'],
        'to'    => ['date_format' => 'toはY/m/d形式。'],
        'direction' => ['in' => '方向はASCまたはDESC。'],
        'message' => [
            'required' => 'メッセージを入力してください',
        ],
        'scenario' => [
            'required' => 'シナリオを選択してください',
        ],
        'scenario_step' => [
            'invalid' => '選択したステップが不正です',
        ],
        'tag' => [
            'required' => 'タグを選択してください',
        ],
    ],

];
