<?php

namespace App\Services\AiServices;

use App\Models\AiGeneratedWebsite;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebsiteStorageService
{
    private const STORAGE_PATH = 'ai-websites';

    public function __construct(
        private WebsiteCodeParser $codeParser,
        private WebsiteSanitizer $sanitizer,
    ) {}

    /**
     * Save website files to storage and database
     */
    public function saveWebsite(
        int $conversationId,
        int $messageId,
        array $files,
        ?string $websiteName = null
    ): array {
        $websiteId = Str::uuid()->toString();
        $basePath = self::STORAGE_PATH . '/' . $websiteId;

        // Sanitize all files before saving
        $files = $this->sanitizer->sanitizeFiles($files);

        $savedFiles = [];

        foreach ($files as $file) {
            $filePath = $basePath . '/' . $file['filename'];
            $content = $file['content'];

            // Rewrite relative paths in HTML files to absolute paths
            if ($file['mime_type'] === 'text/html') {
                $content = $this->sanitizer->rewriteRelativePaths($content, $websiteId);
            }

            Storage::disk('public')->put($filePath, $content);

            $savedFiles[] = [
                'filename' => $file['filename'],
                'file_path' => $filePath,
                'mime_type' => $file['mime_type'],
                'file_size' => strlen($file['content']),
            ];
        }

        // Find entry file
        $entryFile = $this->codeParser->findEntryFile($files) ?? 'index.html';

        // Create database record
        $website = AiGeneratedWebsite::create([
            'uuid' => $websiteId,
            'conversation_id' => $conversationId,
            'message_id' => $messageId,
            'name' => $websiteName ?? 'Generated Website ' . date('Y-m-d H:i:s'),
            'files' => $savedFiles,
            'entry_file' => $entryFile,
            'is_published' => true,
        ]);

        return [
            'website_id' => $websiteId,
            'website_url' => $this->getWebsiteUrl($websiteId),
            'preview_url' => $this->getPreviewUrl($websiteId),
            'files' => $savedFiles,
            'database_id' => $website->id,
        ];
    }

    /**
     * Get public URL for website
     */
    public function getWebsiteUrl(string $websiteId): string
    {
        return url('/websites/' . $websiteId);
    }

    /**
     * Get preview URL for website
     */
    public function getPreviewUrl(string $websiteId): string
    {
        return url('/websites/' . $websiteId . '/preview');
    }

    /**
     * Delete website and all its files
     */
    public function deleteWebsite(string $websiteId): bool
    {
        $basePath = self::STORAGE_PATH . '/' . $websiteId;

        if (Storage::disk('public')->exists($basePath)) {
            Storage::disk('public')->deleteDirectory($basePath);
        }

        return AiGeneratedWebsite::where('uuid', $websiteId)->delete() > 0;
    }

    /**
     * Get website by UUID
     */
    public function getWebsite(string $websiteId): ?AiGeneratedWebsite
    {
        return AiGeneratedWebsite::where('uuid', $websiteId)->first();
    }

    /**
     * Get file content from website
     */
    public function getFileContent(string $websiteId, string $filename): ?string
    {
        $filePath = self::STORAGE_PATH . '/' . $websiteId . '/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            return null;
        }

        return Storage::disk('public')->get($filePath);
    }

    /**
     * Check if file exists in website
     */
    public function fileExists(string $websiteId, string $filename): bool
    {
        $filePath = self::STORAGE_PATH . '/' . $websiteId . '/' . $filename;
        return Storage::disk('public')->exists($filePath);
    }

    /**
     * Update message ID for a website
     */
    public function updateMessageId(string $websiteId, int $messageId): bool
    {
        return AiGeneratedWebsite::where('uuid', $websiteId)
            ->update(['message_id' => $messageId]) > 0;
    }

    /**
     * Get all websites for a conversation
     */
    public function getWebsitesByConversation(int $conversationId): array
    {
        return AiGeneratedWebsite::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Update website files (for modifications)
     */
    public function updateWebsite(string $websiteId, array $files, ?string $newName = null): ?array
    {
        $website = $this->getWebsite($websiteId);

        if (!$website) {
            return null;
        }

        $basePath = self::STORAGE_PATH . '/' . $websiteId;

        // Sanitize and save new files
        $files = $this->sanitizer->sanitizeFiles($files);
        $savedFiles = [];

        foreach ($files as $file) {
            $filePath = $basePath . '/' . $file['filename'];
            $content = $file['content'];

            // Rewrite relative paths in HTML files to absolute paths
            if ($file['mime_type'] === 'text/html') {
                $content = $this->sanitizer->rewriteRelativePaths($content, $websiteId);
            }

            Storage::disk('public')->put($filePath, $content);

            $savedFiles[] = [
                'filename' => $file['filename'],
                'file_path' => $filePath,
                'mime_type' => $file['mime_type'],
                'file_size' => strlen($content),
            ];
        }

        // Update entry file if needed
        $entryFile = $this->codeParser->findEntryFile($files) ?? $website->entry_file;

        // Update database record
        $website->update([
            'files' => $savedFiles,
            'entry_file' => $entryFile,
            'name' => $newName ?? $website->name,
        ]);

        return [
            'website_id' => $websiteId,
            'website_url' => $this->getWebsiteUrl($websiteId),
            'preview_url' => $this->getPreviewUrl($websiteId),
            'files' => $savedFiles,
        ];
    }
}
