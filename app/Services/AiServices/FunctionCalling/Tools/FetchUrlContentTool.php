<?php

namespace App\Services\AiServices\FunctionCalling\Tools;

use App\Services\AiServices\FunctionCalling\ToolExecutionContext;
use App\Services\AiServices\FunctionCalling\ToolResult;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class FetchUrlContentTool extends AbstractTool
{
    private const CACHE_TTL_SECONDS = 3600;
    private const MAX_CONTENT_LENGTH = 50000;
    private const RATE_LIMIT_MAX = 5;
    private const RATE_LIMIT_DECAY = 60;

    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout' => 15,
            'connect_timeout' => 10,
            'verify' => false,
        ]);
    }

    public function getName(): string
    {
        return 'fetch_url_content';
    }

    public function getDescription(): string
    {
        return 'Fetch and read the content of a webpage URL. ' .
               'Use this when the user shares a URL and wants you to read, summarize, or analyze its content. ' .
               'URLのコンテンツを取得して読み取ります。';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'url' => [
                    'type' => 'string',
                    'description' => 'The URL to fetch content from (読み取るURL)',
                ],
            ],
            'required' => ['url'],
        ];
    }

    public function execute(array $arguments, ToolExecutionContext $context): ToolResult
    {
        $url = $arguments['url'] ?? '';

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->error('Invalid URL format. URLの形式が無効です。');
        }

        // Rate limiting
        $rateLimitKey = 'fetch_url_' . ($context->userId ?? $context->conversationId);
        if (!RateLimiter::attempt($rateLimitKey, self::RATE_LIMIT_MAX, fn() => true, self::RATE_LIMIT_DECAY)) {
            return $this->error('Rate limit exceeded. Please wait before fetching more URLs. レート制限を超えました。');
        }

        // Check cache
        $cacheKey = 'url_content_' . md5($url);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            Log::info('FetchUrlContentTool: Returning cached content', ['url' => $url]);
            return $this->success($cached, 'Content fetched (cached). コンテンツを取得しました（キャッシュ）。');
        }

        try {
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'ja,en-US;q=0.7,en;q=0.3',
                ],
            ]);

            $html = $response->getBody()->getContents();
            $content = $this->extractContent($html, $url);

            Cache::put($cacheKey, $content, self::CACHE_TTL_SECONDS);

            Log::info('FetchUrlContentTool: Content fetched', [
                'url' => $url,
                'title' => $content['title'] ?? null,
                'length' => strlen($content['text'] ?? ''),
            ]);

            return $this->success($content, 'Content fetched successfully. コンテンツを取得しました。');

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : null;
            Log::error('FetchUrlContentTool: HTTP request failed', [
                'url' => $url,
                'status_code' => $statusCode,
                'error' => $e->getMessage(),
            ]);
            return $this->error("Failed to fetch URL (HTTP {$statusCode}): " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('FetchUrlContentTool: Failed', ['url' => $url, 'error' => $e->getMessage()]);
            return $this->error('Failed to fetch URL: ' . $e->getMessage());
        }
    }

    public function isAvailable(ToolExecutionContext $context): bool
    {
        return $context->application->type === 'agent';
    }

    private function extractContent(string $html, string $url): array
    {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);

        // Get title
        $titleNodes = $xpath->query('//title');
        $title = $titleNodes->length > 0 ? trim($titleNodes->item(0)->textContent) : null;

        // Get meta description
        $descNodes = $xpath->query('//meta[@name="description"]/@content');
        $description = $descNodes->length > 0 ? $descNodes->item(0)->textContent : null;

        // Try og:description if meta description is empty
        if (empty($description)) {
            $ogDescNodes = $xpath->query('//meta[@property="og:description"]/@content');
            $description = $ogDescNodes->length > 0 ? $ogDescNodes->item(0)->textContent : null;
        }

        // Remove unwanted elements
        $removeSelectors = [
            '//script',
            '//style',
            '//nav',
            '//header',
            '//footer',
            '//aside',
            '//form',
            '//iframe',
            '//noscript',
            '//*[contains(@class, "menu")]',
            '//*[contains(@class, "nav")]',
            '//*[contains(@class, "sidebar")]',
            '//*[contains(@class, "ad")]',
            '//*[contains(@class, "advertisement")]',
            '//*[contains(@class, "comment")]',
            '//*[contains(@class, "social")]',
            '//*[contains(@class, "share")]',
            '//*[contains(@id, "comment")]',
            '//*[contains(@id, "sidebar")]',
        ];

        foreach ($removeSelectors as $selector) {
            $nodes = $xpath->query($selector);
            foreach ($nodes as $node) {
                if ($node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
            }
        }

        // Extract main content - try multiple selectors in order of specificity
        $mainContent = '';
        $contentSelectors = [
            '//article',
            '//main',
            '//*[@role="main"]',
            '//*[contains(@class, "article")]',
            '//*[contains(@class, "post-content")]',
            '//*[contains(@class, "entry-content")]',
            '//*[contains(@class, "content")]',
            '//*[contains(@class, "post")]',
            '//body',
        ];

        foreach ($contentSelectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes->length > 0) {
                $mainContent = $this->getTextContent($nodes->item(0));
                // Accept content if it's substantial enough
                if (strlen($mainContent) > 200) {
                    break;
                }
            }
        }

        // Clean up text - normalize whitespace
        $mainContent = preg_replace('/\s+/', ' ', $mainContent);
        $mainContent = trim($mainContent);

        // Truncate if too long
        if (strlen($mainContent) > self::MAX_CONTENT_LENGTH) {
            $mainContent = mb_substr($mainContent, 0, self::MAX_CONTENT_LENGTH) . '... [truncated]';
        }

        return [
            'url' => $url,
            'title' => $title,
            'description' => $description,
            'text' => $mainContent,
            'word_count' => str_word_count($mainContent),
            'char_count' => mb_strlen($mainContent),
        ];
    }

    private function getTextContent(\DOMNode $node): string
    {
        $text = '';
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text .= ' ' . $child->textContent;
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $text .= ' ' . $this->getTextContent($child);
            }
        }
        return $text;
    }
}
