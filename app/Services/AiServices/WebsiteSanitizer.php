<?php

namespace App\Services\AiServices;

class WebsiteSanitizer
{
    /**
     * Allowed external CDN domains for scripts and styles
     */
    private const ALLOWED_EXTERNAL_DOMAINS = [
        'fonts.googleapis.com',
        'fonts.gstatic.com',
        'cdnjs.cloudflare.com',
        'cdn.jsdelivr.net',
        'unpkg.com',
        'cdn.tailwindcss.com',
        'maxcdn.bootstrapcdn.com',
        'stackpath.bootstrapcdn.com',
        'code.jquery.com',
    ];

    /**
     * Dangerous patterns to remove
     */
    private const DANGEROUS_PATTERNS = [
        // PHP code injection
        '/<\?php/i',
        '/<\?=/i',
        '/<\?/i',

        // Server-side includes
        '/<!--#/i',

        // Data URLs with HTML/JS
        '/data:text\/html/i',

        // Dangerous protocols
        '/file:\/\//i',
        '/ftp:\/\//i',
    ];

    /**
     * Sanitize file content based on MIME type
     */
    public function sanitize(string $content, string $mimeType): string
    {
        // Remove dangerous patterns from all files
        foreach (self::DANGEROUS_PATTERNS as $pattern) {
            $content = preg_replace($pattern, '/* removed */', $content);
        }

        // Apply type-specific sanitization
        return match ($mimeType) {
            'text/html' => $this->sanitizeHtml($content),
            'text/css' => $this->sanitizeCss($content),
            'application/javascript' => $this->sanitizeJavaScript($content),
            default => $content,
        };
    }

    /**
     * Sanitize HTML content
     */
    private function sanitizeHtml(string $html): string
    {
        // Remove dangerous meta tags (refresh/redirect)
        $html = preg_replace('/<meta[^>]*http-equiv\s*=\s*["\']?refresh[^>]*>/i', '', $html);

        // Remove base tags that could redirect
        $html = preg_replace('/<base[^>]*>/i', '', $html);

        // Remove object/embed tags
        $html = preg_replace('/<object[^>]*>.*?<\/object>/si', '', $html);
        $html = preg_replace('/<embed[^>]*>/i', '', $html);

        // Remove applet tags
        $html = preg_replace('/<applet[^>]*>.*?<\/applet>/si', '', $html);

        // Remove iframe with external sources (keep local iframes)
        $html = preg_replace_callback(
            '/<iframe([^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];
                if (preg_match('/src\s*=\s*["\']?(https?:\/\/[^"\'>\s]+)/i', $attrs, $srcMatch)) {
                    $host = parse_url($srcMatch[1], PHP_URL_HOST);
                    if ($host && !$this->isAllowedDomain($host)) {
                        return '<!-- iframe removed for security -->';
                    }
                }
                return $matches[0];
            },
            $html
        );

        // Sanitize script tags with external sources
        $html = preg_replace_callback(
            '/<script([^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];
                if (preg_match('/src\s*=\s*["\']?(https?:\/\/[^"\'>\s]+)/i', $attrs, $srcMatch)) {
                    $host = parse_url($srcMatch[1], PHP_URL_HOST);
                    if ($host && !$this->isAllowedDomain($host)) {
                        return '<!-- external script removed for security --><script>';
                    }
                }
                return $matches[0];
            },
            $html
        );

        // Remove javascript: protocol in href/src
        $html = preg_replace('/\s(href|src)\s*=\s*["\']?javascript:[^"\'>\s]*/i', '', $html);

        // Remove on* event handlers with suspicious content
        $html = preg_replace_callback(
            '/\s(on\w+)\s*=\s*["\']([^"\']*)["\']?/i',
            function ($matches) {
                $handler = strtolower($matches[2]);
                // Allow simple handlers, block fetch/XMLHttpRequest/eval
                if (preg_match('/(fetch|XMLHttpRequest|eval|Function|import)/i', $handler)) {
                    return '';
                }
                return $matches[0];
            },
            $html
        );

        return $html;
    }

    /**
     * Sanitize CSS content
     */
    private function sanitizeCss(string $css): string
    {
        // Remove @import with external URLs (except allowed CDNs)
        $css = preg_replace_callback(
            '/@import\s+(?:url\s*\()?["\']?(https?:\/\/[^"\')\s]+)["\']?\)?/i',
            function ($matches) {
                $host = parse_url($matches[1], PHP_URL_HOST);
                if ($host && !$this->isAllowedDomain($host)) {
                    return '/* external import removed */';
                }
                return $matches[0];
            },
            $css
        );

        // Remove expression() - old IE vulnerability
        $css = preg_replace('/expression\s*\([^)]*\)/i', '/* expression removed */', $css);

        // Remove behavior: url() - IE vulnerability
        $css = preg_replace('/behavior\s*:\s*url\s*\([^)]*\)/i', '/* behavior removed */', $css);

        // Remove -moz-binding - Firefox vulnerability
        $css = preg_replace('/-moz-binding\s*:\s*url\s*\([^)]*\)/i', '/* moz-binding removed */', $css);

        return $css;
    }

    /**
     * Sanitize JavaScript content
     */
    private function sanitizeJavaScript(string $js): string
    {
        // Remove document.cookie access (potential XSS)
        $js = preg_replace('/document\s*\.\s*cookie/i', '/* cookie access removed */', $js);

        // Remove localStorage/sessionStorage sensitive operations
        // Note: This is a basic sanitization, consider more comprehensive approach for production

        return $js;
    }

    /**
     * Check if domain is in allowed list
     */
    private function isAllowedDomain(string $host): bool
    {
        $host = strtolower($host);

        foreach (self::ALLOWED_EXTERNAL_DOMAINS as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate if an external URL is allowed
     */
    public function validateExternalUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (!$host) {
            return false;
        }

        return $this->isAllowedDomain($host);
    }

    /**
     * Sanitize all files in array
     */
    public function sanitizeFiles(array $files): array
    {
        return array_map(function ($file) {
            $file['content'] = $this->sanitize($file['content'], $file['mime_type']);
            return $file;
        }, $files);
    }

    /**
     * Rewrite relative paths in HTML to use absolute paths with website UUID
     */
    public function rewriteRelativePaths(string $html, string $websiteId): string
    {
        $baseUrl = url('/websites/' . $websiteId . '/');

        // Rewrite href attributes for CSS files
        $html = preg_replace_callback(
            '/(<link[^>]*href\s*=\s*["\'])(?!https?:\/\/|\/\/|\/|#)([^"\']+)(["\'][^>]*>)/i',
            function ($matches) use ($baseUrl) {
                return $matches[1] . $baseUrl . $matches[2] . $matches[3];
            },
            $html
        );

        // Rewrite src attributes for JS files
        $html = preg_replace_callback(
            '/(<script[^>]*src\s*=\s*["\'])(?!https?:\/\/|\/\/|\/|#)([^"\']+)(["\'][^>]*>)/i',
            function ($matches) use ($baseUrl) {
                return $matches[1] . $baseUrl . $matches[2] . $matches[3];
            },
            $html
        );

        // Rewrite src attributes for images
        $html = preg_replace_callback(
            '/(<img[^>]*src\s*=\s*["\'])(?!https?:\/\/|\/\/|\/|data:|#)([^"\']+)(["\'][^>]*>)/i',
            function ($matches) use ($baseUrl) {
                return $matches[1] . $baseUrl . $matches[2] . $matches[3];
            },
            $html
        );

        return $html;
    }
}
