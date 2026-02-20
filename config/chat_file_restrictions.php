<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chat File Upload Restrictions
    |--------------------------------------------------------------------------
    |
    | This configuration defines which file extensions are blacklisted
    | from being uploaded in chat messages for security reasons.
    |
    */

    'blacklisted_extensions' => [
        // 実行可能ファイル（特に危険度が高い）
        'exe', 'msi', 'cmd', 'bat', 'com', 'scr', 'pif',
        'sh', 'bash', 'zsh',
        'ps1', // PowerShellスクリプト
        'jar', // Java実行可能
        'bin', 'elf',
        
        // スクリプト系
        'php', 'phtml', 'php3', 'php4', 'php5', 'phar',
        'js', 'ts', // JavaScript/TypeScript
        'pl', // Perl
        'rb', // Ruby  
        'py', 'pyc', 'pyo', // Python
        'asp', 'aspx', 'jsp',
        
        // サーバー設定・マクロ付き文書
        'htaccess', 'config', 'ini', 'conf',
        'docm', 'xlsm', 'pptm', // マクロ付きOfficeファイル
        'reg', // Windows Registry
        'vbs', 'vb', // Visual Basic Script
        'wsf', 'wsh', // Windows Script
        'hta', // HTML Application
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size (in KB)
    |--------------------------------------------------------------------------
    |
    | Maximum file size allowed for chat file uploads
    |
    */
    'max_file_size' => env('CHAT_MAX_FILE_SIZE', 102400), // 10MB

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Additional MIME type validation for security
    |
    */
    'allowed_mime_types' => [
        // Images
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        
        // Documents
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        
        // Text files
        'text/plain', 'text/csv',
        
        // Audio/Video
        'audio/mpeg', 'audio/mp3', 'audio/mp4', 'audio/wav', 'audio/wave', 'audio/x-wav',
        'audio/ogg', 'audio/opus', 'audio/webm',
        'audio/x-m4a', 'audio/m4a', 'audio/aac', 'audio/aacp', 'audio/3gpp', 'audio/3gpp2',
        'application/octet-stream', // Generic binary - sometimes used for audio files
        'video/mp4', 'video/mpeg', 'video/quicktime', 'video/webm', 'video/x-msvideo', 'video/x-matroska',
        'video/3gpp', 'video/3gpp2',
        
        // Archives
        'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
        'application/gzip', 'application/x-tar',
    ],
];