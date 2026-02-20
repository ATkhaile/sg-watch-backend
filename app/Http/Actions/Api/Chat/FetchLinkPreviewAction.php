<?php

namespace App\Http\Actions\Api\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class FetchLinkPreviewAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json([
                'error' => 'URL is required',
                'code' => 400
            ], 400);
        }

        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json([
                'error' => 'Invalid URL format',
                'code' => 400
            ], 400);
        }

        // Cache key based on URL
        $cacheKey = 'link_preview_' . md5($url);

        // Try to get from cache first (cache for 1 hour)
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return response()->json([
                'data' => $cachedData,
                'cached' => true
            ]);
        }

        try {
            // Check if it's a YouTube URL - handle specially
            $youtubeData = $this->parseYoutubeUrl($url);
            if ($youtubeData) {
                // Fetch YouTube page for OG data
                $html = $this->fetchUrl($url);

                if ($html) {
                    $ogData = $this->parseOgTags($html, $url);

                    // Override with YouTube-specific data
                    $ogData['video_id'] = $youtubeData['video_id'];
                    $ogData['is_youtube'] = true;
                    $ogData['embed_url'] = "https://www.youtube.com/embed/{$youtubeData['video_id']}";

                    // Use high quality thumbnail if OG image is missing
                    if (!$ogData['image']) {
                        $ogData['image'] = "https://img.youtube.com/vi/{$youtubeData['video_id']}/maxresdefault.jpg";
                    }

                    $ogData['site_name'] = $ogData['site_name'] ?? 'YouTube';
                    $ogData['favicon'] = 'https://www.youtube.com/favicon.ico';
                } else {
                    // Fallback for YouTube if fetch fails
                    $ogData = [
                        'url' => $url,
                        'title' => 'YouTube Video',
                        'description' => null,
                        'image' => "https://img.youtube.com/vi/{$youtubeData['video_id']}/maxresdefault.jpg",
                        'site_name' => 'YouTube',
                        'type' => 'video',
                        'favicon' => 'https://www.youtube.com/favicon.ico',
                        'video_id' => $youtubeData['video_id'],
                        'is_youtube' => true,
                        'embed_url' => "https://www.youtube.com/embed/{$youtubeData['video_id']}",
                    ];
                }

                // Sanitize data to ensure valid UTF-8
                $ogData = $this->sanitizeUtf8($ogData);

                Cache::put($cacheKey, $ogData, 3600);
                return response()->json([
                    'data' => $ogData,
                    'cached' => false
                ]);
            }

            // Fetch the URL
            $html = $this->fetchUrl($url);

            if (!$html) {
                return response()->json([
                    'error' => 'Failed to fetch URL',
                    'code' => 400
                ], 400);
            }

            // Parse OG meta tags
            $ogData = $this->parseOgTags($html, $url);

            // Sanitize data to ensure valid UTF-8
            $ogData = $this->sanitizeUtf8($ogData);

            // Cache the result for 1 hour
            Cache::put($cacheKey, $ogData, 3600);

            return response()->json([
                'data' => $ogData,
                'cached' => false
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch link preview: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Fetch URL content using GuzzleHttp Client
     */
    private function fetchUrl(string $url): ?string
    {
        try {
            $httpClient = new Client([
                'timeout' => 10,
                'verify' => false, // Skip SSL verification for compatibility
            ]);

            $response = $httpClient->get($url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'ja,en-US;q=0.7,en;q=0.3',
                ],
            ]);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $html = $response->getBody()->getContents();
                return $this->convertToUtf8($html);
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Failed to fetch URL for link preview: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert HTML content to UTF-8 encoding
     */
    private function convertToUtf8(string $html): string
    {
        // Try to detect encoding from meta charset or content-type
        $encoding = null;

        // Check for <meta charset="...">
        if (preg_match('/<meta[^>]+charset=["\']?([^"\'\s>]+)/i', $html, $matches)) {
            $encoding = $matches[1];
        }
        // Check for <meta http-equiv="Content-Type" content="text/html; charset=...">
        elseif (preg_match('/<meta[^>]+content=["\'][^"\']*charset=([^"\'\s;]+)/i', $html, $matches)) {
            $encoding = $matches[1];
        }

        // Normalize encoding names
        if ($encoding) {
            $encoding = strtoupper(trim($encoding));
            $encoding = str_replace(['SHIFT-JIS', 'SHIFT_JIS', 'SJIS'], 'SJIS-win', $encoding);
            $encoding = str_replace(['EUC-JP', 'EUCJP'], 'EUC-JP', $encoding);
        }

        // If encoding detected and not UTF-8, convert
        if ($encoding && !in_array($encoding, ['UTF-8', 'UTF8'])) {
            $converted = @mb_convert_encoding($html, 'UTF-8', $encoding);
            if ($converted !== false) {
                return $converted;
            }
        }

        // Try to detect encoding automatically if not found or conversion failed
        $detectedEncoding = mb_detect_encoding($html, ['UTF-8', 'SJIS-win', 'EUC-JP', 'ISO-8859-1', 'ASCII'], true);

        if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
            $converted = @mb_convert_encoding($html, 'UTF-8', $detectedEncoding);
            if ($converted !== false) {
                return $converted;
            }
        }

        // Remove invalid UTF-8 sequences as last resort
        return mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    }

    /**
     * Recursively sanitize array/string to ensure valid UTF-8
     */
    private function sanitizeUtf8(mixed $data): mixed
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeUtf8'], $data);
        }

        if (is_string($data)) {
            // Remove invalid UTF-8 sequences
            $clean = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
            // Also remove control characters except newlines and tabs
            return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $clean);
        }

        return $data;
    }

    /**
     * Parse YouTube URL and extract video ID
     */
    private function parseYoutubeUrl(string $url): ?array
    {
        $patterns = [
            // Standard YouTube URLs: youtube.com/watch?v=VIDEO_ID
            '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
            // Short URLs: youtu.be/VIDEO_ID
            '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            // Embed URLs: youtube.com/embed/VIDEO_ID
            '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            // Shorts: youtube.com/shorts/VIDEO_ID
            '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return [
                    'video_id' => $matches[1],
                ];
            }
        }

        return null;
    }

    /**
     * Parse Open Graph meta tags from HTML
     */
    private function parseOgTags(string $html, string $url): array
    {
        $data = [
            'url' => $url,
            'title' => null,
            'description' => null,
            'image' => null,
            'site_name' => null,
            'type' => null,
            'favicon' => null,
        ];

        // Create DOM parser
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);

        // Get OG tags
        $metaTags = $xpath->query('//meta[@property or @name]');

        /** @var \DOMElement $meta */
        foreach ($metaTags as $meta) {
            $property = $meta->getAttribute('property') ?: $meta->getAttribute('name');
            $content = $meta->getAttribute('content');

            switch (strtolower($property)) {
                case 'og:title':
                    $data['title'] = $content;
                    break;
                case 'og:description':
                    $data['description'] = $content;
                    break;
                case 'og:image':
                    $data['image'] = $this->makeAbsoluteUrl($content, $url);
                    break;
                case 'og:site_name':
                    $data['site_name'] = $content;
                    break;
                case 'og:type':
                    $data['type'] = $content;
                    break;
                case 'description':
                    if (!$data['description']) {
                        $data['description'] = $content;
                    }
                    break;
                case 'twitter:title':
                    if (!$data['title']) {
                        $data['title'] = $content;
                    }
                    break;
                case 'twitter:description':
                    if (!$data['description']) {
                        $data['description'] = $content;
                    }
                    break;
                case 'twitter:image':
                    if (!$data['image']) {
                        $data['image'] = $this->makeAbsoluteUrl($content, $url);
                    }
                    break;
            }
        }

        // Fallback to title tag if no OG title
        if (!$data['title']) {
            $titleNodes = $xpath->query('//title');
            if ($titleNodes->length > 0) {
                $data['title'] = trim($titleNodes->item(0)->textContent);
            }
        }

        // Get favicon
        $faviconNodes = $xpath->query('//link[@rel="icon" or @rel="shortcut icon"]');
        if ($faviconNodes->length > 0) {
            $data['favicon'] = $this->makeAbsoluteUrl($faviconNodes->item(0)->getAttribute('href'), $url);
        } else {
            // Default favicon path
            $parsedUrl = parse_url($url);
            $data['favicon'] = ($parsedUrl['scheme'] ?? 'https') . '://' . ($parsedUrl['host'] ?? '') . '/favicon.ico';
        }

        // Truncate description if too long
        if ($data['description'] && strlen($data['description']) > 200) {
            $data['description'] = substr($data['description'], 0, 200) . '...';
        }

        return $data;
    }

    /**
     * Convert relative URL to absolute URL
     */
    private function makeAbsoluteUrl(?string $relativeUrl, string $baseUrl): ?string
    {
        if (!$relativeUrl) {
            return null;
        }

        // Already absolute
        if (preg_match('/^https?:\/\//', $relativeUrl)) {
            return $relativeUrl;
        }

        // Protocol-relative URL
        if (str_starts_with($relativeUrl, '//')) {
            $parsedBase = parse_url($baseUrl);
            return ($parsedBase['scheme'] ?? 'https') . ':' . $relativeUrl;
        }

        // Relative URL
        $parsedBase = parse_url($baseUrl);
        $baseHost = ($parsedBase['scheme'] ?? 'https') . '://' . ($parsedBase['host'] ?? '');

        if (str_starts_with($relativeUrl, '/')) {
            return $baseHost . $relativeUrl;
        }

        // Relative to current path
        $basePath = $parsedBase['path'] ?? '/';
        $baseDir = dirname($basePath);
        return $baseHost . $baseDir . '/' . $relativeUrl;
    }
}
