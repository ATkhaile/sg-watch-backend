<?php

return [
    'job' => [
        'list' => [
            'message' => 'インポートジョブ一覧が正常に取得されました',
        ],
        'detail' => [
            'message' => 'インポートジョブ詳細が正常に取得されました',
        ],
        'delete_error' => [
            'success' => 'エラージョブが正常に削除されました',
            'failed' => 'エラージョブの削除に失敗しました',
        ],
        'stop' => [
            'success' => 'インポートジョブが正常に停止されました',
            'failed' => 'インポートジョブの停止に失敗しました',
        ],
        'resume' => [
            'success' => 'インポートジョブが正常に再開されました',
            'failed' => 'インポートジョブの再開に失敗しました',
        ],
        'create' => [
            'success' => 'インポートジョブが正常に作成されました',
            'failed' => 'インポートジョブの作成に失敗しました',
        ],
    ],
    'validation' => [
        'page' => [
            'integer' => 'ページは整数で入力してください',
            'min' => 'ページは1以上で入力してください'
        ],
        'per_page' => [
            'integer' => '件数は整数で入力してください',
            'min' => '件数は1以上で入力してください'
        ],
        'status' => [
            'string' => 'ステータスは文字列で入力してください',
            'in' => 'ステータスは以下のいずれかである必要があります: ' . implode(', ', \App\Enums\JobStatus::getValues())
        ],
        'id' => [
            'required' => 'IDは必須です',
            'integer' => 'IDは整数で入力してください',
            'exists' => 'インポートジョブが見つかりません',
        ],
        'skip_error' => [
            'boolean' => 'skip_error は true か false である必要があります',
        ],
        'sanitize' => [
            'boolean' => 'sanitize は true か false である必要があります',
        ],
        'file' => [
            'required' => 'ファイルは必須です',
            'file' => 'ファイルは有効なファイルで入力してください',
            'mimes' => 'ファイルはCSV形式で入力してください',
            'max' => 'ファイルサイズは10GB以下で入力してください'
        ],
    ],
];
