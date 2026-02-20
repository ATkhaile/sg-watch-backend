<?php

namespace App\Services\AiServices;

class WebsiteCodeParser
{
    /**
     * Parse AI response and extract website files
     * Expected format: ```language:filename\ncode\n```
     */
    public function parse(string $response): array
    {
        $files = [];

        // Match code blocks with filename pattern: ```language:filename
        $pattern = '/```(\w+):([^\n]+)\n(.*?)```/s';

        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $language = $match[1];
                $filename = trim($match[2]);
                $content = $match[3];

                // Validate filename
                if (!$this->isValidFilename($filename)) {
                    continue;
                }

                $files[] = [
                    'filename' => $filename,
                    'content' => $content,
                    'language' => $language,
                    'mime_type' => $this->getMimeType($language, $filename),
                ];
            }
        }

        // Fallback: try to extract code blocks without filename pattern
        if (empty($files)) {
            $files = $this->parseFallback($response);
        }

        return $files;
    }

    /**
     * Fallback parser for standard markdown code blocks
     */
    private function parseFallback(string $response): array
    {
        $files = [];

        // Match standard code blocks: ```language\ncode\n```
        $pattern = '/```(html|css|javascript|js)\n(.*?)```/s';

        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {
            $htmlCount = 0;
            $cssCount = 0;
            $jsCount = 0;

            foreach ($matches as $match) {
                $language = $match[1];
                $content = $match[2];

                switch ($language) {
                    case 'html':
                        $filename = $htmlCount === 0 ? 'index.html' : "page{$htmlCount}.html";
                        $htmlCount++;
                        break;
                    case 'css':
                        $filename = $cssCount === 0 ? 'styles.css' : "styles{$cssCount}.css";
                        $cssCount++;
                        break;
                    case 'javascript':
                    case 'js':
                        $filename = $jsCount === 0 ? 'script.js' : "script{$jsCount}.js";
                        $jsCount++;
                        $language = 'javascript';
                        break;
                    default:
                        continue 2;
                }

                $files[] = [
                    'filename' => $filename,
                    'content' => $content,
                    'language' => $language,
                    'mime_type' => $this->getMimeType($language, $filename),
                ];
            }
        }

        return $files;
    }

    /**
     * Get MIME type from language or file extension
     */
    private function getMimeType(string $language, string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $mimeTypes = [
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'javascript' => 'application/javascript',
            'json' => 'application/json',
            'svg' => 'image/svg+xml',
            'txt' => 'text/plain',
            'md' => 'text/markdown',
        ];

        return $mimeTypes[$extension] ?? $mimeTypes[$language] ?? 'text/plain';
    }

    /**
     * Check if files contain valid website files (HTML, CSS, JS)
     */
    public function hasWebsiteFiles(array $files): bool
    {
        $websiteMimeTypes = ['text/html', 'text/css', 'application/javascript'];

        foreach ($files as $file) {
            if (in_array($file['mime_type'], $websiteMimeTypes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if filename is valid and safe
     */
    private function isValidFilename(string $filename): bool
    {
        // Check for directory traversal
        if (str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
            return false;
        }

        // Check allowed extensions
        $allowedExtensions = ['html', 'htm', 'css', 'js', 'json', 'svg', 'txt', 'md'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return in_array($extension, $allowedExtensions);
    }

    /**
     * Find the entry file (main HTML file)
     */
    public function findEntryFile(array $files): ?string
    {
        // Priority: index.html > first .html file > null
        foreach ($files as $file) {
            if ($file['filename'] === 'index.html') {
                return 'index.html';
            }
        }

        foreach ($files as $file) {
            if (str_ends_with($file['filename'], '.html') || str_ends_with($file['filename'], '.htm')) {
                return $file['filename'];
            }
        }

        return null;
    }
}
