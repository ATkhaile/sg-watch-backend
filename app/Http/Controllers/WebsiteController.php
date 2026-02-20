<?php

namespace App\Http\Controllers;

use App\Models\AiGeneratedWebsite;
use App\Services\AiServices\WebsiteStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebsiteController extends BaseController
{
    public function __construct(
        private WebsiteStorageService $websiteStorageService,
    ) {}

    /**
     * Show website entry file or specific file
     */
    public function show(string $uuid, ?string $path = null): Response
    {
        $website = AiGeneratedWebsite::where('uuid', $uuid)
            ->where('is_published', true)
            ->first();

        if (!$website) {
            abort(404, 'Website not found');
        }

        // Determine which file to serve
        $filename = $path ?? $website->entry_file;

        // Security: prevent directory traversal
        $filename = basename($filename);

        // Get file content
        $content = $this->websiteStorageService->getFileContent($uuid, $filename);

        if ($content === null) {
            abort(404, 'File not found');
        }

        // Determine MIME type
        $mimeType = $this->getMimeType($filename);

        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Show website in preview mode (with iframe wrapper)
     */
    public function preview(string $uuid): Response
    {
        $website = AiGeneratedWebsite::where('uuid', $uuid)->first();

        if (!$website) {
            abort(404, 'Website not found');
        }

        $iframeUrl = url('/websites/' . $uuid);
        $websiteName = htmlspecialchars($website->name, ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {$websiteName}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .preview-header {
            background: #1a1a2e;
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .preview-title { font-size: 14px; font-weight: 500; }
        .preview-actions { display: flex; gap: 10px; }
        .preview-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        .preview-btn-primary {
            background: #4f46e5;
            color: white;
        }
        .preview-btn-primary:hover { background: #4338ca; }
        .preview-btn-secondary {
            background: #374151;
            color: white;
        }
        .preview-btn-secondary:hover { background: #4b5563; }
        .preview-frame {
            position: fixed;
            top: 52px;
            left: 0;
            right: 0;
            bottom: 0;
            border: none;
        }
        .device-toggle { display: flex; gap: 5px; }
        .device-btn {
            padding: 6px 12px;
            border: 1px solid #4b5563;
            background: transparent;
            color: #9ca3af;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .device-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: white;
        }
    </style>
</head>
<body>
    <div class="preview-header">
        <div class="preview-title">{$websiteName}</div>
        <div class="device-toggle">
            <button class="device-btn active" onclick="setDevice('100%')">Desktop</button>
            <button class="device-btn" onclick="setDevice('768px')">Tablet</button>
            <button class="device-btn" onclick="setDevice('375px')">Mobile</button>
        </div>
        <div class="preview-actions">
            <a href="{$iframeUrl}" target="_blank" class="preview-btn preview-btn-primary">Open in New Tab</a>
        </div>
    </div>
    <iframe id="preview-iframe" class="preview-frame" src="{$iframeUrl}"></iframe>
    <script>
        function setDevice(width) {
            const iframe = document.getElementById('preview-iframe');
            const buttons = document.querySelectorAll('.device-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            if (width === '100%') {
                iframe.style.width = '100%';
                iframe.style.left = '0';
                iframe.style.margin = '0';
            } else {
                iframe.style.width = width;
                iframe.style.left = '50%';
                iframe.style.transform = 'translateX(-50%)';
                iframe.style.boxShadow = '0 0 20px rgba(0,0,0,0.3)';
            }
        }
    </script>
</body>
</html>
HTML;

        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Get MIME type from filename
     */
    private function getMimeType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $mimeTypes = [
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'txt' => 'text/plain',
            'md' => 'text/markdown',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
