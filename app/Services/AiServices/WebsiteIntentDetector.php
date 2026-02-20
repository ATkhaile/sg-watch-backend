<?php

namespace App\Services\AiServices;

class WebsiteIntentDetector
{
    /**
     * Keywords indicating website creation intent
     */
    private const WEBSITE_KEYWORDS = [
        // English
        'website',
        'web page',
        'webpage',
        'landing page',
        'homepage',
        'home page',
        'web site',
        'html page',
        'html file',

        // Vietnamese
        'trang web',
        'website',
        'trang chủ',
        'landing page',
        'trang html',

        // Japanese
        'ウェブサイト',
        'ホームページ',
        'ランディングページ',
        'ウェブページ',
        'サイト',
        'HTMLページ',
    ];

    /**
     * Action keywords that indicate creation/generation intent
     */
    private const ACTION_KEYWORDS = [
        // English
        'create',
        'make',
        'build',
        'generate',
        'design',
        'develop',
        'code',
        'write',

        // Vietnamese
        'tạo',
        'làm',
        'xây dựng',
        'thiết kế',
        'viết',
        'tạo ra',

        // Japanese
        '作成',
        '作って',
        '生成',
        'デザイン',
        '構築',
        '開発',
        '書いて',
    ];

    /**
     * Detect if the message is requesting website creation
     */
    public function detect(string $message): bool
    {
        $normalizedMessage = $this->normalizeMessage($message);

        // Check for website keywords
        $hasWebsiteKeyword = false;
        foreach (self::WEBSITE_KEYWORDS as $keyword) {
            if (str_contains($normalizedMessage, $this->normalizeMessage($keyword))) {
                $hasWebsiteKeyword = true;
                break;
            }
        }

        if (!$hasWebsiteKeyword) {
            return false;
        }

        // Check for action keywords
        foreach (self::ACTION_KEYWORDS as $keyword) {
            if (str_contains($normalizedMessage, $this->normalizeMessage($keyword))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect if the message is requesting website modification
     */
    public function isModificationRequest(string $message): bool
    {
        $modifyKeywords = [
            // English
            'modify',
            'change',
            'update',
            'edit',
            'fix',
            'improve',
            'adjust',
            'alter',

            // Vietnamese
            'sửa',
            'chỉnh',
            'thay đổi',
            'cập nhật',
            'điều chỉnh',

            // Japanese
            '修正',
            '変更',
            '更新',
            '編集',
            '改善',
            '調整',
        ];

        $normalizedMessage = $this->normalizeMessage($message);

        foreach ($modifyKeywords as $keyword) {
            if (str_contains($normalizedMessage, $this->normalizeMessage($keyword))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract website name from message if mentioned
     */
    public function extractWebsiteName(string $message): ?string
    {
        // Try to extract quoted text as potential name
        if (preg_match('/["\']([^"\']+)["\']/', $message, $matches)) {
            $name = trim($matches[1]);
            if (strlen($name) > 0 && strlen($name) < 100) {
                return $name;
            }
        }

        // Try to extract "called X" or "named X"
        if (preg_match('/(?:called|named|titled)\s+["\']?([^"\'.,]+)["\']?/i', $message, $matches)) {
            $name = trim($matches[1]);
            if (strlen($name) > 0 && strlen($name) < 100) {
                return $name;
            }
        }

        return null;
    }

    /**
     * Normalize message for comparison
     */
    private function normalizeMessage(string $message): string
    {
        return mb_strtolower(trim($message));
    }

    /**
     * Get confidence score for website intent (0-1)
     */
    public function getConfidenceScore(string $message): float
    {
        $normalizedMessage = $this->normalizeMessage($message);
        $score = 0.0;

        // Count website keywords matches
        $websiteMatches = 0;
        foreach (self::WEBSITE_KEYWORDS as $keyword) {
            if (str_contains($normalizedMessage, $this->normalizeMessage($keyword))) {
                $websiteMatches++;
            }
        }

        // Count action keywords matches
        $actionMatches = 0;
        foreach (self::ACTION_KEYWORDS as $keyword) {
            if (str_contains($normalizedMessage, $this->normalizeMessage($keyword))) {
                $actionMatches++;
            }
        }

        // Calculate score
        if ($websiteMatches > 0) {
            $score += min(0.5, $websiteMatches * 0.25);
        }

        if ($actionMatches > 0) {
            $score += min(0.5, $actionMatches * 0.25);
        }

        return min(1.0, $score);
    }
}
