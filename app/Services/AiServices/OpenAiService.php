<?php

namespace App\Services\AiServices;

use App\Services\AiServices\Traits\HttpClientTrait;
use App\Services\AiServices\Traits\MimeTypeHelperTrait;
use App\Services\OfficeFileExtractor;

class OpenAiService
{
    use HttpClientTrait;
    use MimeTypeHelperTrait;

    public function chat(array $messages, array $config): array
    {
        $baseUrl = $config['baseUrl'] ?? 'https://api.openai.com/v1';
        $url = rtrim($baseUrl, '/') . '/responses';

        $systemInstruction = null;
        $input = [];

        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemInstruction = $msg['content'];
            } else {
                $content = $this->buildContent($msg['content'], $msg['files'] ?? null);
                $input[] = [
                    'role' => $msg['role'],
                    'content' => $content,
                ];
            }
        }

        $query = [
            'model' => $config['model'],
            'input' => $input,
            'max_output_tokens' => $config['maxTokens'] ?? 2000,
        ];

        // Reasoning models (GPT-5, o1, o3 series) do not support temperature parameter
        if (!$this->isReasoningModel($config['model'])) {
            $query['temperature'] = $config['temperature'] ?? 0.7;
        }

        if ($systemInstruction) {
            $query['instructions'] = $systemInstruction;
        }

        // Add tools if provided (for function calling)
        if (!empty($config['tools'])) {
            $query['tools'] = $config['tools'];
        }

        $response = $this->httpPost($url, $query, [
            'Authorization' => 'Bearer ' . $config['apiKey'],
        ], 300);

        if ($response === false) {
            throw new \Exception('OpenAI API error: Failed to connect');
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('OpenAI API error: ' . ($data['error']['message'] ?? $response));
        }

        $answer = '';
        $generatedFiles = [];

        foreach ($data['output'] ?? [] as $output) {
            if ($output['type'] === 'message') {
                foreach ($output['content'] ?? [] as $content) {
                    if ($content['type'] === 'output_text') {
                        $answer .= $content['text'];
                    } elseif ($content['type'] === 'output_image') {
                        $generatedFiles[] = $this->processGeneratedImage($content);
                    } elseif ($content['type'] === 'output_file') {
                        $generatedFiles[] = $this->processGeneratedFile($content);
                    }
                }
            }
        }

        $generatedFiles = array_filter($generatedFiles);

        return [
            'answer' => $answer,
            'query' => $query,
            'message_tokens' => $data['usage']['input_tokens'] ?? 0,
            'answer_tokens' => $data['usage']['output_tokens'] ?? 0,
            'generated_files' => array_values($generatedFiles),
            'metadata' => [
                'model' => $data['model'] ?? $config['model'],
                'finish_reason' => $data['status'] ?? null,
            ],
            'raw_response' => $data, // Include raw response for function calling
        ];
    }

    /**
     * Chat with raw response for function calling support
     */
    public function chatWithFunctionCalling(array $messages, array $config): array
    {
        $baseUrl = $config['baseUrl'] ?? 'https://api.openai.com/v1';
        $url = rtrim($baseUrl, '/') . '/responses';

        $systemInstruction = null;
        $input = [];

        // Check if code_interpreter is enabled in tools
        $hasCodeInterpreter = $this->hasCodeInterpreterTool($config['tools'] ?? []);
        $codeInterpreterFileIds = [];

        foreach ($messages as $msg) {
            // Handle function_call and function_call_output from tool calling flow
            // These must be passed through as-is to OpenAI
            if (isset($msg['type']) && in_array($msg['type'], ['function_call', 'function_call_output'])) {
                $input[] = $msg;
                continue;
            }

            $role = $msg['role'] ?? null;
            if ($role === 'system') {
                $systemInstruction = $msg['content'];
            } elseif ($role !== null) {
                $files = $msg['files'] ?? null;

                // If code_interpreter is enabled, upload supported files to OpenAI Files API
                if ($hasCodeInterpreter && !empty($files)) {
                    $uploadResult = $this->uploadFilesForCodeInterpreter($files, $config['apiKey'], $baseUrl);
                    $codeInterpreterFileIds = array_merge($codeInterpreterFileIds, $uploadResult['file_ids']);
                    // Keep non-code-interpreter files for normal processing
                    $files = $uploadResult['remaining_files'];
                }

                $content = $this->buildContent($msg['content'] ?? '', $files);
                $input[] = [
                    'role' => $role,
                    'content' => $content,
                ];
            }
        }

        $query = [
            'model' => $config['model'],
            'input' => $input,
            'max_output_tokens' => $config['maxTokens'] ?? 2000,
        ];

        // Reasoning models (GPT-5, o1, o3 series) do not support temperature parameter
        if (!$this->isReasoningModel($config['model'])) {
            $query['temperature'] = $config['temperature'] ?? 0.7;
        }

        if ($systemInstruction) {
            $query['instructions'] = $systemInstruction;
        }

        if (!empty($config['tools'])) {
            $tools = $config['tools'];

            // If code_interpreter is enabled and has supported files, attach file_ids
            // Otherwise keep code_interpreter without file_ids - AI can use it for code in text messages
            if ($hasCodeInterpreter && !empty($codeInterpreterFileIds)) {
                $tools = $this->updateCodeInterpreterWithFileIds($tools, $codeInterpreterFileIds);
            }

            $query['tools'] = $tools;

            // If code_interpreter is enabled, request full output including files
            if ($hasCodeInterpreter) {
                $query['include'] = ['code_interpreter_call.outputs'];
            }
        }
        $response = $this->httpPost($url, $query, [
            'Authorization' => 'Bearer ' . $config['apiKey'],
        ], 300);

        if ($response === false) {
            throw new \Exception('OpenAI API error: Failed to connect');
        }

        $data = json_decode($response, true);

        if (isset($data['error'])) {
            throw new \Exception('OpenAI API error: ' . ($data['error']['message'] ?? $response));
        }

        // Return raw response with metadata for function calling processing
        return [
            'raw_response' => $data,
            'query' => $query,
            'message_tokens' => $data['usage']['input_tokens'] ?? 0,
            'answer_tokens' => $data['usage']['output_tokens'] ?? 0,
        ];
    }

    /**
     * Check if code_interpreter tool is enabled in tools config
     */
    private function hasCodeInterpreterTool(array $tools): bool
    {
        foreach ($tools as $tool) {
            if (isset($tool['type']) && $tool['type'] === 'code_interpreter') {
                return true;
            }
        }
        return false;
    }

    /**
     * Upload files to OpenAI Files API for code interpreter
     * Returns array with 'file_ids' and 'remaining_files'
     */
    private function uploadFilesForCodeInterpreter(array $files, string $apiKey, string $baseUrl): array
    {
        $fileIds = [];
        $remainingFiles = [];

        foreach ($files as $file) {
            $mimeType = $file['mime_type'] ?? 'application/octet-stream';
            $fileName = $file['name'] ?? 'file';
            $storagePath = $file['storage_path'] ?? null;

            // Check if this file type is supported by code interpreter
            if ($storagePath && $this->isCodeInterpreterSupported($mimeType, $fileName)) {
                $fullPath = storage_path('app/public/' . $storagePath);
                if (file_exists($fullPath)) {
                    $fileId = $this->uploadFileToOpenAi($fullPath, $fileName, $apiKey, $baseUrl);
                    if ($fileId) {
                        $fileIds[] = $fileId;
                        continue;
                    }
                }
            }

            // Keep file for normal processing if not uploaded to code interpreter
            $remainingFiles[] = $file;
        }

        return [
            'file_ids' => $fileIds,
            'remaining_files' => $remainingFiles,
        ];
    }

    /**
     * Upload a single file to OpenAI Files API
     * @return string|null File ID or null on failure
     */
    private function uploadFileToOpenAi(string $filePath, string $fileName, string $apiKey, string $baseUrl): ?string
    {
        $url = rtrim($baseUrl, '/') . '/files';

        // Build multipart form data
        $boundary = uniqid('boundary_', true);
        $fileContent = file_get_contents($filePath);

        if ($fileContent === false) {
            return null;
        }

        $body = '';
        // Add purpose field
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"purpose\"\r\n\r\n";
        $body .= "user_data\r\n";

        // Add file field
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"file\"; filename=\"{$fileName}\"\r\n";
        $body .= "Content-Type: application/octet-stream\r\n\r\n";
        $body .= $fileContent . "\r\n";
        $body .= "--{$boundary}--\r\n";

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    "Authorization: Bearer {$apiKey}",
                    "Content-Type: multipart/form-data; boundary={$boundary}",
                ],
                'content' => $body,
                'timeout' => 120,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        if (isset($data['id'])) {
            return $data['id'];
        }

        return null;
    }

    /**
     * Update code_interpreter tool config with file_ids
     */
    private function updateCodeInterpreterWithFileIds(array $tools, array $fileIds): array
    {
        return array_map(function ($tool) use ($fileIds) {
            if (isset($tool['type']) && $tool['type'] === 'code_interpreter') {
                // Ensure container exists
                if (!isset($tool['container'])) {
                    $tool['container'] = ['type' => 'auto'];
                }
                // Add file_ids to container
                $tool['container']['file_ids'] = $fileIds;
            }
            return $tool;
        }, $tools);
    }

    public function getModels(string $apiKey, ?string $baseUrl = null): array
    {
        $url = $baseUrl ? rtrim($baseUrl, '/') . '/models' : 'https://api.openai.com/v1/models';

        $response = $this->httpGet($url, ['Authorization' => 'Bearer ' . $apiKey]);

        if ($response === false) {
            throw new \Exception('Failed to fetch OpenAI models');
        }

        $data = json_decode($response, true);

        return collect($data['data'] ?? [])
            ->filter(fn($model) => str_contains($model['id'], 'gpt') || str_contains($model['id'], 'o1') || str_contains($model['id'], 'o3'))
            ->map(fn($model) => [
                'id' => $model['id'],
                'name' => $model['id'],
            ])
            ->sortBy('id')
            ->values()
            ->toArray();
    }

    private function buildContent(string $text, ?array $files): array|string
    {
        if (empty($files)) {
            return $text;
        }

        $content = [];
        $extractedTexts = [];
        $attachedFiles = [];
        $extractor = new OfficeFileExtractor();

        foreach ($files as $file) {
            $mimeType = $file['mime_type'] ?? 'application/octet-stream';
            $publicUrl = $file['url'] ?? null;
            $storagePath = $file['storage_path'] ?? null;
            $fileName = $file['name'] ?? 'file';

            if (str_starts_with($mimeType, 'image/')) {
                if ($publicUrl) {
                    $content[] = [
                        'type' => 'input_image',
                        'image_url' => $publicUrl,
                    ];
                }
            } elseif ($mimeType === 'application/pdf') {
                if ($publicUrl) {
                    $content[] = [
                        'type' => 'input_file',
                        'file_url' => $publicUrl,
                    ];
                }
            } elseif ($extractor->needsExtraction($mimeType)) {
                if ($storagePath) {
                    $fullPath = storage_path('app/public/' . $storagePath);
                    $extractedText = $extractor->extractText($fullPath, $mimeType);
                    if ($extractedText) {
                        $extractedTexts[] = "--- Content from file: {$fileName} ---\n{$extractedText}\n--- End of file ---";
                    }
                }
            } elseif ($this->isTextBasedFile($mimeType, $fileName)) {
                // Handle code files and text-based files
                if ($storagePath) {
                    $fullPath = storage_path('app/public/' . $storagePath);
                    if (file_exists($fullPath)) {
                        $fileContent = file_get_contents($fullPath);
                        if ($fileContent !== false && mb_check_encoding($fileContent, 'UTF-8')) {
                            $extractedTexts[] = "--- Content from file: {$fileName} ---\n{$fileContent}\n--- End of file ---";
                        }
                    }
                }
            } elseif ($this->isArchiveFile($mimeType, $fileName)) {
                // Handle ZIP and archive files - notify AI about the attachment
                $fileSize = $file['file_size'] ?? 0;
                $fileSizeFormatted = $this->formatFileSize($fileSize);
                $attachedFiles[] = "- {$fileName} (Archive file, {$fileSizeFormatted})";
            } else {
                // Other binary files - just notify AI about the attachment
                $fileSize = $file['file_size'] ?? 0;
                $fileSizeFormatted = $this->formatFileSize($fileSize);
                $attachedFiles[] = "- {$fileName} ({$mimeType}, {$fileSizeFormatted})";
            }
        }

        $finalText = $text;

        // Add attached files notification
        if (!empty($attachedFiles)) {
            $attachmentInfo = "[Attached files that cannot be read directly:]\n" . implode("\n", $attachedFiles);
            $finalText = $attachmentInfo . "\n\n" . $finalText;
        }

        // Add extracted text content
        if (!empty($extractedTexts)) {
            $finalText = implode("\n\n", $extractedTexts) . "\n\n" . $finalText;
        }

        $content[] = [
            'type' => 'input_text',
            'text' => $finalText,
        ];

        return $content;
    }

    private function isTextBasedFile(string $mimeType, string $fileName): bool
    {
        // Check by MIME type
        $textMimeTypes = [
            'text/plain',
            'text/html',
            'text/css',
            'text/csv',
            'text/xml',
            'text/markdown',
            'application/json',
            'application/xml',
            'application/javascript',
            'application/x-javascript',
            'application/typescript',
            'application/x-httpd-php',
            'application/x-sh',
            'application/x-python',
            'application/sql',
        ];

        if (in_array($mimeType, $textMimeTypes) || str_starts_with($mimeType, 'text/')) {
            return true;
        }

        // Check by file extension for code files
        $codeExtensions = [
            // Web
            'html', 'htm', 'css', 'js', 'jsx', 'ts', 'tsx', 'vue', 'svelte',
            // PHP
            'php', 'phtml', 'blade.php',
            // Python
            'py', 'pyw', 'pyx',
            // Java/Kotlin
            'java', 'kt', 'kts',
            // C/C++
            'c', 'cpp', 'cc', 'cxx', 'h', 'hpp', 'hxx',
            // C#
            'cs',
            // Go
            'go',
            // Rust
            'rs',
            // Ruby
            'rb', 'erb',
            // Swift
            'swift',
            // Dart/Flutter
            'dart',
            // Shell
            'sh', 'bash', 'zsh', 'fish',
            // Data/Config
            'json', 'xml', 'yaml', 'yml', 'toml', 'ini', 'cfg', 'conf',
            'env', 'env.example', 'env.local',
            // Markdown/Docs
            'md', 'mdx', 'rst', 'txt',
            // SQL
            'sql',
            // Other
            'graphql', 'gql', 'proto', 'thrift',
            'dockerfile', 'makefile', 'cmake',
            'gitignore', 'gitattributes', 'editorconfig',
            'htaccess', 'nginx', 'apache',
            // IFC (Industry Foundation Classes - BIM)
            'ifc',
            // Logs
            'log',
        ];

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $baseFileName = strtolower(basename($fileName));

        // Check exact filename matches (Dockerfile, Makefile, etc.)
        $exactMatches = ['dockerfile', 'makefile', 'gemfile', 'rakefile', 'procfile', 'vagrantfile'];
        if (in_array($baseFileName, $exactMatches)) {
            return true;
        }

        return in_array($extension, $codeExtensions);
    }

    private function isArchiveFile(string $mimeType, string $fileName): bool
    {
        $archiveMimeTypes = [
            'application/zip',
            'application/x-zip-compressed',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            'application/gzip',
            'application/x-gzip',
            'application/x-tar',
            'application/x-bzip2',
        ];

        if (in_array($mimeType, $archiveMimeTypes)) {
            return true;
        }

        $archiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2', 'xz', 'tgz'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        return in_array($extension, $archiveExtensions);
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    private function processGeneratedImage(array $content): ?array
    {
        $imageData = null;
        $mimeType = 'image/png';

        if (!empty($content['image_base64'])) {
            $imageData = base64_decode($content['image_base64']);
            $mimeType = $content['mime_type'] ?? 'image/png';
        } elseif (!empty($content['image_url'])) {
            $imageData = $this->downloadFile($content['image_url']);
            $mimeType = $this->getMimeTypeFromUrl($content['image_url']) ?? 'image/png';
        } elseif (!empty($content['url'])) {
            $imageData = $this->downloadFile($content['url']);
            $mimeType = $this->getMimeTypeFromUrl($content['url']) ?? 'image/png';
        }

        if (!$imageData) {
            return null;
        }

        return [
            'type' => 'image',
            'data' => $imageData,
            'mime_type' => $mimeType,
            'original_name' => 'generated_image_' . date('YmdHis') . '.' . $this->getExtensionFromMimeType($mimeType),
        ];
    }

    private function processGeneratedFile(array $content): ?array
    {
        $fileData = null;
        $mimeType = 'application/octet-stream';
        $filename = $content['filename'] ?? 'file';

        if (!empty($content['file_base64'])) {
            $fileData = base64_decode($content['file_base64']);
            $mimeType = $content['mime_type'] ?? $this->getMimeTypeFromFilename($filename);
        } elseif (!empty($content['file_url'])) {
            $fileData = $this->downloadFile($content['file_url']);
            $mimeType = $content['mime_type'] ?? $this->getMimeTypeFromUrl($content['file_url']);
        } elseif (!empty($content['url'])) {
            $fileData = $this->downloadFile($content['url']);
            $mimeType = $content['mime_type'] ?? $this->getMimeTypeFromUrl($content['url']);
        }

        if (!$fileData) {
            return null;
        }

        return [
            'type' => 'file',
            'data' => $fileData,
            'mime_type' => $mimeType,
            'original_name' => $filename,
        ];
    }

    private function downloadFile(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 60,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $data = @file_get_contents($url, false, $context);

        return $data !== false ? $data : null;
    }

    /**
     * Check if the model is a reasoning model (GPT-5, o1, o3, o4 series)
     * These models do not support temperature and other sampling parameters
     */
    private function isReasoningModel(string $model): bool
    {
        $model = strtolower($model);

        // GPT-5 series (all GPT-5 models are reasoning models)
        if (str_contains($model, 'gpt-5')) {
            return true;
        }

        // o1 series (o1, o1-mini, o1-pro, o1-preview, etc.)
        if (preg_match('/\bo1\b/', $model)) {
            return true;
        }

        // o3 series (o3, o3-mini, o3-pro, o3-deep-research, etc.)
        if (preg_match('/\bo3\b/', $model)) {
            return true;
        }

        // o4 series (o4-mini, o4-mini-deep-research, etc.)
        if (preg_match('/\bo4\b/', $model)) {
            return true;
        }

        return false;
    }

    /**
     * Download a file from OpenAI container
     *
     * @param string $containerId The container ID
     * @param string $fileId The file ID (cfile_xxx)
     * @param string $apiKey API key
     * @param string|null $baseUrl Optional custom base URL
     * @return array|null Returns ['data' => binary, 'filename' => string] or null on failure
     */
    public function downloadContainerFile(string $containerId, string $fileId, string $apiKey, ?string $baseUrl = null): ?array
    {
        $baseUrl = $baseUrl ?? 'https://api.openai.com/v1';
        $url = rtrim($baseUrl, '/') . "/containers/{$containerId}/files/{$fileId}/content";

        \Illuminate\Support\Facades\Log::info('Downloading container file', [
            'container_id' => $containerId,
            'file_id' => $fileId,
            'url' => $url,
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    "Authorization: Bearer {$apiKey}",
                ],
                'timeout' => 120,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $data = @file_get_contents($url, false, $context);

        if ($data === false) {
            \Illuminate\Support\Facades\Log::error('Failed to download container file', [
                'container_id' => $containerId,
                'file_id' => $fileId,
            ]);
            return null;
        }

        // Check for error response (JSON)
        $jsonCheck = json_decode($data, true);
        if (isset($jsonCheck['error'])) {
            \Illuminate\Support\Facades\Log::error('Container file download error', [
                'error' => $jsonCheck['error'],
            ]);
            return null;
        }

        // Get filename from response headers if available
        $filename = $fileId;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (stripos($header, 'content-disposition:') !== false) {
                    if (preg_match('/filename="?([^";\s]+)"?/i', $header, $matches)) {
                        $filename = $matches[1];
                    }
                }
            }
        }

        \Illuminate\Support\Facades\Log::info('Container file downloaded successfully', [
            'file_id' => $fileId,
            'data_size' => strlen($data),
            'filename' => $filename,
        ]);

        return [
            'data' => $data,
            'filename' => $filename,
        ];
    }

    /**
     * Get container file metadata
     *
     * @param string $containerId The container ID
     * @param string $fileId The file ID
     * @param string $apiKey API key
     * @param string|null $baseUrl Optional custom base URL
     * @return array|null File metadata or null on failure
     */
    public function getContainerFileMetadata(string $containerId, string $fileId, string $apiKey, ?string $baseUrl = null): ?array
    {
        $baseUrl = $baseUrl ?? 'https://api.openai.com/v1';
        $url = rtrim($baseUrl, '/') . "/containers/{$containerId}/files/{$fileId}";

        $response = $this->httpGet($url, ['Authorization' => 'Bearer ' . $apiKey]);

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * List all files in a container
     *
     * @param string $containerId The container ID
     * @param string $apiKey API key
     * @param string|null $baseUrl Optional custom base URL
     * @return array List of files
     */
    public function listContainerFiles(string $containerId, string $apiKey, ?string $baseUrl = null): array
    {
        $baseUrl = $baseUrl ?? 'https://api.openai.com/v1';
        $url = rtrim($baseUrl, '/') . "/containers/{$containerId}/files";

        $response = $this->httpGet($url, ['Authorization' => 'Bearer ' . $apiKey]);

        if ($response === false) {
            return [];
        }

        $data = json_decode($response, true);
        return $data['data'] ?? [];
    }
}
