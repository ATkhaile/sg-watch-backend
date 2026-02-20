<?php

namespace App\Services\AiServices;

use App\Models\AiMessageAttachment;
use App\Services\AiServices\Traits\MimeTypeHelperTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    use MimeTypeHelperTrait;

    private const MAX_FILE_SIZE = 100 * 1024 * 1024; // 100MB per file
    private const MAX_CONVERSATION_SIZE = 100 * 1024 * 1024; // 100MB per conversation

    public function uploadFiles(int $conversationId, array $files): array
    {
        $uploadedFiles = [];

        $currentSize = AiMessageAttachment::whereHas('message', function ($query) use ($conversationId) {
            $query->where('conversation_id', $conversationId);
        })->sum('file_size');

        foreach ($files as $file) {
            $filePath = $file['path'];
            $fileSize = filesize($filePath);

            if ($fileSize > self::MAX_FILE_SIZE) {
                continue;
            }

            if (($currentSize + $fileSize) > self::MAX_CONVERSATION_SIZE) {
                break;
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uuidFileName = Str::uuid() . '.' . $extension;
            $storagePath = 'ai-chat/' . $uuidFileName;

            Storage::disk('public')->put($storagePath, file_get_contents($filePath));

            $publicUrl = asset('storage/' . $storagePath);

            $uploadedFiles[] = [
                'name' => $file['name'],
                'mime_type' => $file['mime_type'],
                'url' => $publicUrl,
                'storage_path' => $storagePath,
                'file_size' => $fileSize,
            ];

            $currentSize += $fileSize;
        }

        return $uploadedFiles;
    }

    public function saveAttachments(int $messageId, array $uploadedFiles): array
    {
        $savedAttachments = [];

        foreach ($uploadedFiles as $file) {
            $attachment = AiMessageAttachment::create([
                'ai_message_id' => $messageId,
                'original_name' => $file['name'],
                'file_path' => $file['storage_path'],
                'mime_type' => $file['mime_type'],
                'file_size' => $file['file_size'],
            ]);

            $savedAttachments[] = [
                'id' => $attachment->id,
                'original_name' => $attachment->original_name,
                'file_url' => $attachment->file_url,
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
            ];
        }

        return $savedAttachments;
    }

    public function downloadFile(string $url): ?string
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

    public function saveGeneratedFile(string $data, string $mimeType, string $type, ?string $originalName = null): array
    {
        // Prefer extension from original filename over MIME type (more accurate for code files)
        $extension = null;
        if ($originalName) {
            $originalExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!empty($originalExtension)) {
                $extension = $originalExtension;
            }
        }

        // Fallback to MIME type if no extension from filename
        if (!$extension) {
            $extension = $this->getExtensionFromMimeType($mimeType);
        }

        $uuidFileName = Str::uuid() . '.' . $extension;
        $storagePath = 'ai-chat/generated/' . $uuidFileName;

        Storage::disk('public')->put($storagePath, $data);

        $fileSize = strlen($data);

        if (!$originalName) {
            $originalName = $type === 'image'
                ? 'generated_image_' . date('YmdHis') . '.' . $extension
                : 'generated_file_' . date('YmdHis') . '.' . $extension;
        }

        // Use API route for code files to bypass web server blocking
        $codeExtensions = ['php', 'py', 'js', 'ts', 'rb', 'java', 'c', 'cpp', 'cs', 'sh'];
        if (in_array($extension, $codeExtensions)) {
            $relativePath = 'api/v1/ai/files/' . $uuidFileName;
        } else {
            $relativePath = 'storage/' . $storagePath;
        }

        return [
            'type' => $type,
            'original_name' => $originalName,
            'file_path' => $storagePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_url' => $relativePath,
        ];
    }

    public function transformGeneratedFilesUrl(array $files): array
    {
        return array_map(function ($file) {
            if (isset($file['file_url']) && !str_starts_with($file['file_url'], 'http')) {
                $file['file_url'] = asset($file['file_url']);
            }
            return $file;
        }, $files);
    }

    public function saveGeneratedFilesFromResponse(array $generatedFiles): array
    {
        $savedFiles = [];

        foreach ($generatedFiles as $file) {
            if (isset($file['data'])) {
                $savedFiles[] = $this->saveGeneratedFile(
                    $file['data'],
                    $file['mime_type'] ?? 'application/octet-stream',
                    $file['type'] ?? 'file',
                    $file['original_name'] ?? null
                );
            }
        }

        return $savedFiles;
    }

    /**
     * Save code interpreter generated files as message attachments
     * This saves the files and creates attachment records in the database
     *
     * @param int $messageId The AI message ID
     * @param array $generatedFiles Array of generated files from code interpreter
     * @return array Array of saved attachment information
     */
    public function saveCodeInterpreterFilesAsAttachments(int $messageId, array $generatedFiles): array
    {
        $savedAttachments = [];

        foreach ($generatedFiles as $file) {
            // Only process files that have binary data and are from code_interpreter
            if (!isset($file['data']) || ($file['source'] ?? null) !== 'code_interpreter') {
                continue;
            }

            // Save file to storage
            $savedFile = $this->saveGeneratedFile(
                $file['data'],
                $file['mime_type'] ?? 'application/octet-stream',
                $file['type'] ?? 'file',
                $file['original_name'] ?? null
            );

            // Create attachment record in database
            $attachment = AiMessageAttachment::create([
                'ai_message_id' => $messageId,
                'original_name' => $savedFile['original_name'],
                'file_path' => $savedFile['file_path'],
                'mime_type' => $savedFile['mime_type'],
                'file_size' => $savedFile['file_size'],
            ]);

            $savedAttachments[] = [
                'id' => $attachment->id,
                'original_name' => $attachment->original_name,
                'file_url' => $attachment->file_url,
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
                'source' => 'code_interpreter',
            ];
        }

        return $savedAttachments;
    }
}
