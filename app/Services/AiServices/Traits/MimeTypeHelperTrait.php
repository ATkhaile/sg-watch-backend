<?php

namespace App\Services\AiServices\Traits;

trait MimeTypeHelperTrait
{
    /**
     * MIME types supported by OpenAI Code Interpreter tool
     * @see https://platform.openai.com/docs/guides/tools-code-interpreter
     */
    protected static array $codeInterpreterSupportedMimeTypes = [
        // Code files
        'text/x-c' => 'c',
        'text/x-csharp' => 'cs',
        'text/x-c++' => 'cpp',
        'text/x-java' => 'java',
        'text/x-php' => 'php',
        'text/x-python' => 'py',
        'text/x-script.python' => 'py',
        'text/x-ruby' => 'rb',
        'text/x-tex' => 'tex',
        'text/javascript' => 'js',
        'application/typescript' => 'ts',
        'application/x-sh' => 'sh',
        // Document files
        'text/csv' => 'csv',
        'application/csv' => 'csv',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/pdf' => 'pdf',
        // Text files
        'text/html' => 'html',
        'text/plain' => 'txt',
        'text/css' => 'css',
        'text/markdown' => 'md',
        'application/json' => 'json',
        'application/xml' => 'xml',
        'text/xml' => 'xml',
        // Image files
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        // Archive files
        'application/octet-stream' => 'pkl',
        'application/x-tar' => 'tar',
        'application/zip' => 'zip',
    ];

    /**
     * Check if a file's MIME type is supported by OpenAI Code Interpreter
     */
    protected function isCodeInterpreterSupported(string $mimeType, string $fileName): bool
    {
        // Check by MIME type
        if (isset(self::$codeInterpreterSupportedMimeTypes[$mimeType])) {
            return true;
        }

        // Check by file extension
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $supportedExtensions = [
            'c', 'cs', 'cpp', 'csv', 'doc', 'docx', 'html', 'java', 'json',
            'md', 'pdf', 'php', 'pptx', 'py', 'rb', 'tex', 'txt', 'css',
            'js', 'sh', 'ts', 'jpeg', 'jpg', 'gif', 'pkl', 'png', 'tar',
            'xlsx', 'xml', 'zip',
        ];

        return in_array($extension, $supportedExtensions);
    }

    protected function getExtensionFromMimeType(string $mimeType): string
    {
        $map = [
            // Image files
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            // Document files
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            // Text/Data files
            'text/plain' => 'txt',
            'text/html' => 'html',
            'text/csv' => 'csv',
            'text/css' => 'css',
            'text/markdown' => 'md',
            'application/json' => 'json',
            'application/xml' => 'xml',
            'text/xml' => 'xml',
            'application/zip' => 'zip',
            'application/x-tar' => 'tar',
            // Code files (from code interpreter)
            'text/javascript' => 'js',
            'application/javascript' => 'js',
            'text/x-python' => 'py',
            'text/x-script.python' => 'py',
            'application/x-python-code' => 'py',
            'text/x-php' => 'php',
            'application/x-php' => 'php',
            'text/x-java' => 'java',
            'text/x-java-source' => 'java',
            'text/x-c' => 'c',
            'text/x-c++' => 'cpp',
            'text/x-csharp' => 'cs',
            'text/x-ruby' => 'rb',
            'application/x-ruby' => 'rb',
            'application/typescript' => 'ts',
            'text/typescript' => 'ts',
            'application/x-sh' => 'sh',
            'text/x-shellscript' => 'sh',
            'text/x-tex' => 'tex',
        ];

        return $map[$mimeType] ?? 'bin';
    }

    protected function getMimeTypeFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        return $this->getMimeTypeFromFilename($path);
    }

    protected function getMimeTypeFromFilename(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $map = [
            // Image files
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            // Document files
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            // Text/Data files
            'txt' => 'text/plain',
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'csv' => 'text/csv',
            'md' => 'text/markdown',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'zip' => 'application/zip',
            'tar' => 'application/x-tar',
            // Code files
            'js' => 'text/javascript',
            'ts' => 'application/typescript',
            'py' => 'text/x-python',
            'php' => 'text/x-php',
            'java' => 'text/x-java',
            'c' => 'text/x-c',
            'cpp' => 'text/x-c++',
            'cs' => 'text/x-csharp',
            'rb' => 'text/x-ruby',
            'sh' => 'application/x-sh',
            'tex' => 'text/x-tex',
        ];

        return $map[$extension] ?? 'application/octet-stream';
    }
}
