<?php

namespace App\Enums;

/**
 * Enum for supported file types in media upload
 */
class FileType
{
    public const PDF = 'pdf';
    public const ALL = 'all';
    public const CSV = 'csv';
    public const EXCEL = 'excel';
    public const DOCS = 'docs';
    public const IMG = 'img';
    public const VIDEO = 'video';
    public const AUDIO = 'audio';
    public const GIF = 'gif';

    /**
     * Mime types for each file type
     */
    public const MIME_TYPES = [
        self::PDF => ['application/pdf'],
        self::CSV => ['text/csv', 'application/csv', 'application/vnd.ms-excel'],
        self::EXCEL => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
        ],
        self::DOCS => [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        self::IMG => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'],
        self::VIDEO => [
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo', // avi
            'video/x-matroska', // mkv
            'video/webm',
        ],
        self::AUDIO => [
            'audio/mpeg',    // mp3
            'audio/wav',
            'audio/ogg',
            'audio/mp4',
            'audio/webm',
        ],
        self::GIF => [
            'image/gif',
        ],
    ];

    /**
     * Map of file extensions to file types
     */
    public const EXTENSION_MAP = [
        // PDF
        'pdf'  => self::PDF,

        // CSV
        'csv'  => self::CSV,

        // Excel
        'xls'  => self::EXCEL,
        'xlsx' => self::EXCEL,

        // Docs
        'doc'  => self::DOCS,
        'docx' => self::DOCS,

        // Images
        'jpg'  => self::IMG,
        'jpeg' => self::IMG,
        'png'  => self::IMG,
        'gif'  => self::IMG,
        'webp' => self::IMG,
        'bmp'  => self::IMG,

        // Videos
        'mp4'  => self::VIDEO,
        'mov'  => self::VIDEO,
        'avi'  => self::VIDEO,
        'mkv'  => self::VIDEO,
        'webm' => self::VIDEO,

        // Audio
        'mp3'  => self::AUDIO,
        'wav'  => self::AUDIO,
        'ogg'  => self::AUDIO,
        'm4a'  => self::AUDIO,

        // GIF
        'gif'  => self::GIF,
    ];

    /**
     * Get all supported types
     * @return array
     */
    public static function all(): array
    {
        return [
            self::ALL,
            self::PDF,
            self::CSV,
            self::EXCEL,
            self::DOCS,
            self::IMG,
            self::VIDEO,
            self::AUDIO,
            self::GIF,
        ];
    }

    /**
     * Get allowed mime types by type
     * @param string $type
     * @return array
     */
    public static function getMimeTypesByType(string $type): array
    {
        return self::MIME_TYPES[$type] ?? [];
    }

    /**
     * Get file type by file extension
     * @param string $extension
     * @return string|null
     */
    public static function getTypeByExtension(string $extension): ?string
    {
        return self::EXTENSION_MAP[strtolower($extension)] ?? null;
    }
}
